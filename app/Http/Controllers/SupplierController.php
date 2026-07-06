<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $this->supplierService->storeSupplier($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $this->supplierService->deleteSupplier($id);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }

    // Mengupdate data supplier yang ada
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ]);

    $this->supplierService->updateSupplier($id, $validated);

    return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui!');
}
}