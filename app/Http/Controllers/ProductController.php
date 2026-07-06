<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Menampilkan halaman tabel produk
    public function index()
    {
        $products = $this->productService->getAllProducts();
        
        // Ambil data untuk opsi dropdown di form tambah produk
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    // Menyimpan produk baru
    // Menyimpan produk baru
    public function store(Request $request)
    {
        // Sesuaikan validasi dengan nama kolom asli di database/model Anda
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

    // Menghapus produk
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // Mengupdate data produk yang ada
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'purchase_price' => 'required|numeric|min:0', 
        'selling_price' => 'required|numeric|min:0',  
        'minimum_stock' => 'required|integer|min:0',  
        'sku' => 'nullable|string|max:50|unique:products,sku,' . $id, // Diabaikan jika SKU milik produk ini sendiri
        'description' => 'nullable|string',
    ]);

    $this->productService->updateProduct($id, $validated);

    return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui!');
}
}