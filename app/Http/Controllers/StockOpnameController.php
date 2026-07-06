<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockOpnameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    protected $opnameService;

    // Inject Service ke dalam Controller
    public function __construct(StockOpnameService $opnameService)
    {
        $this->opnameService = $opnameService;
    }

    public function index()
    {
        // Panggil service untuk ambil riwayat
        $opnames = $this->opnameService->getOpnameHistory();
        $products = Product::all();

        return view('admin.opnames.index', compact('opnames', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'system_stock'   => 'required|integer|min:0',
            'physical_stock' => 'required|integer|min:0',
            'notes'          => 'nullable|string',
        ]);

        // Alirkan proses ke Service dengan menyertakan ID user yang sedang login
        $this->opnameService->processOpname($validated, Auth::id());

        return redirect()->route('opnames.index')->with('success', 'Stock Opname berhasil diproses dan stok produk diperbarui!');
    }
}