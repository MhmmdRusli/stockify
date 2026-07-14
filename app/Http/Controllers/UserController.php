<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar pengguna (Hanya Admin)
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => ['required', Rule::in(['Admin', 'Manajer Gudang', 'Staff Gudang'])],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    // Memperbarui data user (Nama, Email, Role, & Password opsional)
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // --- PROTEKSI TAMBAHAN ---
    // Jika user yang diedit adalah Admin, dan dia bukan Admin yang sedang login
    if ($user->role === 'Admin' && auth()->user()->role === 'Admin' && $user->id !== auth()->id()) {
        return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk mengedit sesama Admin.');
    }
    // -------------------------

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'password' => 'nullable|string|min:6',
        'role'     => ['required', Rule::in(['Admin', 'Manajer Gudang', 'Staff Gudang'])],
    ]);

    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
    ];

    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    $user->update($updateData);

    return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    // --- PROTEKSI TAMBAHAN ---
    // Mencegah Admin menghapus Admin lain
    if ($user->role === 'Admin' && $user->id !== auth()->id()) {
        return redirect()->route('users.index')->with('error', 'Anda tidak memiliki izin untuk menghapus sesama Admin.');
    }
    // -------------------------

    // Mencegah Admin tidak sengaja menghapus dirinya sendiri
    if (auth()->id() == $user->id) {
        return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!');
    }

    $user->delete();

    return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
}
}