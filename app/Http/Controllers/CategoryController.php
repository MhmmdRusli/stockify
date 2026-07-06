<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    // Inject CategoryService ke Controller
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // 1. Menampilkan semua kategori
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Menyimpan kategori baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->storeCategory($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. Mengupdate kategori
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->categoryService->updateCategory($id, $validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. Menghapus kategori
    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}