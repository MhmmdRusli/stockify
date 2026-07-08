<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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

    // Menangani aksi unduh berkas ekspor Excel produk
    public function export()
    {
        return Excel::download(new ProductsExport, 'data_produk_' . date('Y-m-d') . '.xlsx');
    }

    // Menangani aksi import massal produk dari file Excel (.xlsx)
    public function import(Request $request)
    {
        // Proteksi backend: Staff Gudang tidak boleh import produk
        if (Auth::check() && Auth::user()->role === 'Staff Gudang') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengimpor data produk.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120', // maks 5MB
        ]);

        $import = new ProductsImport;

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect()->route('products.index')
                ->with('error', 'Import gagal: ' . $e->getMessage());
        }

        // SkipsOnFailure melewati baris yang gagal validasi tanpa melempar exception,
        // jadi kita cek manual apakah ada baris yang gagal lalu tampilkan alasannya.
        if ($import->failures()->isNotEmpty()) {
            $messages = [];
            foreach ($import->failures() as $failure) {
                $messages[] = 'Baris ' . $failure->row() . ' (' . $failure->attribute() . '): ' . implode(', ', $failure->errors());
            }
            return redirect()->route('products.index')
                ->withErrors($messages)
                ->with('error', 'Sebagian baris gagal diimpor, periksa detail error di bawah.');
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diimpor dari file Excel!');
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