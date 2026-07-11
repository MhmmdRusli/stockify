<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\Category; 
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\RestockTaskNotification;
use App\Notifications\StockTransactionPendingNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\DatabaseNotification;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

class StockTransactionController extends Controller
{
    protected $transactionService;

    public function __construct(StockTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    // ==========================================
    // 🚚 FITUR: BARANG MASUK
    // ==========================================
    public function masukIndex(Request $request)
    {
        // 🆕 Tandai notifikasi terkait sebagai sudah dibaca saat halaman ini dibuka lewat link notifikasi
        if ($request->filled('notification_id')) {
            auth()->user()->unreadNotifications()->where('id', $request->notification_id)->first()?->markAsRead();
        }

        $transactions = StockTransaction::where('type', 'in')->with('product')->latest()->get();
        $categories = Category::all();
        $products = Product::all();

        return view('admin.transactions.masuk', compact('transactions', 'products', 'categories'));
    }

    public function masukStore(Request $request)
    {
        $request->validate([
            'new_product_name'  => 'required|string|max:255',
            'category_id'       => 'required',
            'quantity'          => 'required|integer|min:1',
        ]);

        try {
            $categoryId = $request->category_id;

            if ($request->category_id === 'NEW_CATEGORY') {
                $request->validate(['new_category_name' => 'required|string|max:255']);

                $newCat = \App\Models\Category::create([
                    'name' => $request->new_category_name
                ]);
                $categoryId = $newCat->id;
            }

            $defaultSupplier = \App\Models\Supplier::first();
            $supplierId = $defaultSupplier ? $defaultSupplier->id : null;

            $sku = $request->sku ?? 'SKU-' . strtoupper(uniqid());

            $newProduct = \App\Models\Product::create([
                'name'           => $request->new_product_name,
                'category_id'    => $categoryId,
                'supplier_id'    => $supplierId,
                'sku'            => $sku,
                'stock'          => 0,
                'purchase_price' => 0,
                'selling_price'  => 0,
                'minimum_stock'  => 0,
            ]);

            $transaction = \App\Models\StockTransaction::create([
                'product_id'   => $newProduct->id,
                'user_id'      => auth()->id(),
                'type'         => 'in',
                'quantity'     => $request->quantity,
                'date'         => now()->toDateString(),
                'status'       => 'Pending',
                'notes'        => $request->notes ?? 'Pengajuan barang masuk baru oleh Staff',
            ]);

            // 🆕 Notifikasi ke semua Manajer Gudang: ada draft barang masuk baru yang perlu diverifikasi
            $managers = User::where('role', 'Manajer Gudang')->get();
            if ($managers->isNotEmpty()) {
                Notification::send($managers, new StockTransactionPendingNotification($transaction, auth()->user()));
            }

            return redirect()->route('barang.masuk.index')->with('success', 'Draft barang masuk baru berhasil diajukan! Menunggu verifikasi manajer.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan draft: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 📦 FITUR: BARANG KELUAR
    // ==========================================
    public function keluarIndex(Request $request)
    {
        // 🆕 Tandai notifikasi terkait sebagai sudah dibaca
        if ($request->filled('notification_id')) {
            auth()->user()->unreadNotifications()->where('id', $request->notification_id)->first()?->markAsRead();
        }

        $transactions = StockTransaction::where('type', 'out')->with('product')->latest()->get();
        $products = Product::all();

        return view('admin.transactions.keluar', compact('transactions', 'products'));
    }

    public function keluarStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Gagal mengajukan! Stok di gudang tidak mencukupi.');
        }

        $validated['type'] = 'out';
        $validated['date'] = now()->toDateString();
        $validated['user_id'] = auth()->id() ?? 1;
        $validated['status'] = 'Pending';

        $transaction = StockTransaction::create($validated);

        // 🆕 Notifikasi ke semua Staff Gudang: ada pengajuan barang keluar yang perlu disiapkan
        $staffGudang = User::where('role', 'Staff Gudang')->get();
        if ($staffGudang->isNotEmpty()) {
            Notification::send($staffGudang, new StockTransactionPendingNotification($transaction, auth()->user()));
        }

        return redirect()->back()->with('success', 'Pengajuan barang keluar berhasil dibuat! Menunggu konfirmasi Staff.');
    }

    // ==========================================
    // ⚡ TOMBOL SOP: KONFIRMASI & TOLAK
    // ==========================================
    public function konfirmasi($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya!');
        }

        $product = Product::findOrFail($transaction->product_id);

        if ($transaction->type === 'in') {
            $product->increment('stock', $transaction->quantity);
            $transaction->update([
                'status' => 'Diterima',
                'approved_by' => auth()->id(),
            ]);
        } else if ($transaction->type === 'out') {
            if ($product->stock < $transaction->quantity) {
                return redirect()->back()->with('error', 'Gagal konfirmasi! Stok aktual di gudang tidak cukup.');
            }
            $product->decrement('stock', $transaction->quantity);
            $transaction->update([
                'status' => 'Dikeluarkan',
                'approved_by' => auth()->id(),
            ]);
        }

        // 🆕 Hapus semua notifikasi terkait transaksi ini (dari semua penerima),
        // supaya badge notifikasi tidak "menggantung" setelah tugas selesai
        DatabaseNotification::where('data->transaction_id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi Berhasil Dikonfirmasi! Stok telah diperbarui.');
    }

    public function tolak($id)
    {
        $transaction = StockTransaction::findOrFail($id);

        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya!');
        }

        $transaction->update([
            'status' => 'Ditolak',
            'approved_by' => auth()->id(),
        ]);

        // 🆕 Hapus notifikasi terkait transaksi yang ditolak
        DatabaseNotification::where('data->transaction_id', $id)->delete();

        return redirect()->back()->with('success', 'Transaksi telah ditolak! Stok produk tidak berubah.');
    }

    public function print()
    {
        $transactions = $this->transactionService->getAllTransactions();
        return view('admin.transactions.print', compact('transactions'));
    }

    // ==========================================
    // 📊 FITUR LAPORAN
    // ==========================================
    public function stockReport()
    {
        $products = Product::with('category')->get();
        return view('report.stock', compact('products'));
    }

    public function transactionReport()
    {
        $transactions = StockTransaction::with(['product', 'user'])->latest()->get();
        return view('report.transaction', compact('transactions'));
    }

    public function userActivityReport()
    {
        $transactions = StockTransaction::with(['user', 'approvedBy', 'product'])->latest()->get();

        $activities = collect();

        foreach ($transactions as $trx) {
            $activities->push((object)[
                'created_at' => $trx->created_at,
                'user'       => $trx->user,
                'product'    => $trx->product,
                'type'       => $trx->type,
                'action'     => 'pengajuan',
                'quantity'   => $trx->quantity,
            ]);

            if ($trx->approved_by) {
                $activities->push((object)[
                    'created_at' => $trx->updated_at,
                    'user'       => $trx->approvedBy,
                    'product'    => $trx->product,
                    'type'       => $trx->type,
                    'action'     => $trx->status === 'Ditolak' ? 'tolak' : 'konfirmasi',
                    'quantity'   => $trx->quantity,
                ]);
            }
        }

        $activities = $activities->sortByDesc('created_at')->values();

        return view('report.user_activity', compact('activities'));
    }

    // ==========================================
    // 🟢 EXPORT EXCEL
    // ==========================================
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'laporan_stok_'.now()->format('Y-m-d').'.xlsx');
    }

    // ==========================================
    // 🔴 EXPORT PDF
    // ==========================================
    public function exportPdf()
    {
        $products = Product::with('category')->get();
        $pdf = Pdf::loadView('report.stock_pdf', compact('products'));
        return $pdf->download('laporan_stok_'.now()->format('Y-m-d').'.pdf');
    }

    // ==========================================
    // 🔵 IMPORT EXCEL
    // ==========================================
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:2048'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data produk berhasil di-import massal!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data! Periksa kembali format kolom berkas Anda.');
        }
    }

    public function kirimTugasRestock(\App\Models\Product $product)
    {
        if (strtolower(auth()->user()->role) !== 'manajer gudang') {
            abort(403);
        }

        $staffGudang = User::where('role', 'Staff Gudang')->get();
        if ($staffGudang->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada akun Staff Gudang.');
        }

        Notification::send($staffGudang, new RestockTaskNotification($product, auth()->user()));

        return redirect()->back()->with('success', "Tugas restock untuk \"{$product->name}\" berhasil dikirim.");
    }

    public function restockForm(\App\Models\Product $product, Request $request)
    {
        if (strtolower(auth()->user()->role) !== 'staff gudang') {
            abort(403);
        }

        if ($request->filled('notification_id')) {
            auth()->user()->unreadNotifications()->where('id', $request->notification_id)->first()?->markAsRead();
        }

        return view('admin.transactions.restock_form', compact('product'));
    }

    public function restockStore(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $transaction = StockTransaction::create([
            'product_id' => $validated['product_id'],
            'user_id'    => auth()->id(),
            'type'       => 'in',
            'quantity'   => $validated['quantity'],
            'date'       => now()->toDateString(),
            'status'     => 'Pending',
            'notes'      => $validated['notes'] ?? 'Draf restock dari Staff Gudang.',
        ]);

        // 🆕 Notifikasi ke semua Manajer Gudang: ada draf restock yang perlu diverifikasi
        $managers = User::where('role', 'Manajer Gudang')->get();
        if ($managers->isNotEmpty()) {
            Notification::send($managers, new StockTransactionPendingNotification($transaction, auth()->user()));
        }

        return redirect()->route('barang.masuk.index')->with('success', 'Draf restock terkirim ke Manajer.');
    }
}