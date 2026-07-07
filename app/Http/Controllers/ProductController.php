<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Menampilkan halaman tabel produk (Staff, Manajer, Admin BISA MASUK)
    public function index()
    {
        $products = $this->productService->getAllProducts();
        
        // Ambil data untuk opsi dropdown di form tambah produk
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    // Menyimpan produk baru (Staff Gudang DIBATASI)
    public function store(Request $request)
    {
        // Proteksi backend: Jika Staff Gudang mencoba bypass, gagalkan dengan 403
        if (Auth::check() && Auth::user()->role === 'Staff Gudang') {
            abort(403, 'Anda tidak memiliki hak akses untuk menambahkan produk.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0', 
            'selling_price' => 'required|numeric|min:0',  
            'minimum_stock' => 'required|integer|min:0',  
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
        ]);

        $this->productService->storeProduct($validated);

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    // Mengupdate data produk yang ada (Staff Gudang DIBATASI)
    public function update(Request $request, $id)
    {
        // Proteksi backend: Jika Staff Gudang mencoba bypass, gagalkan dengan 403
        if (Auth::check() && Auth::user()->role === 'Staff Gudang') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data produk.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0', 
            'selling_price' => 'required|numeric|min:0',  
            'minimum_stock' => 'required|integer|min:0',  
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $id,
            'description' => 'nullable|string',
        ]);

        $this->productService->updateProduct($id, $validated);

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // Menghapus produk (Staff Gudang DIBATASI)
    public function destroy($id)
    {
        // Proteksi backend: Jika Staff Gudang mencoba bypass, gagalkan dengan 403
        if (Auth::check() && Auth::user()->role === 'Staff Gudang') {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus produk.');
        }

        $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}