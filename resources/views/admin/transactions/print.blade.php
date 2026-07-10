<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang Gudang</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        /* Pengaturan khusus saat halaman dicetak ke kertas atau disimpan ke PDF */
        @media print {
            .no-print { display: none !important; }
            body { 
                background-color: white !important; 
                padding: 0 !important;
                font-family: 'Times New Roman', Times, serif; /* Font formal untuk cetak fisik */
            }
            .print-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
            th {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
        }
    </style>
</head> 
<body class="bg-gray-100 p-4 sm:p-8 text-gray-900 font-sans">

    <div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <span class="text-sm text-gray-600 font-medium">Pratinjau Dokumen Cetak</span>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded shadow cursor-pointer transition-colors">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded cursor-pointer transition-colors">
                Tutup
            </button>
        </div>
    </div>

    <div class="print-container max-w-4xl mx-auto bg-white p-8 rounded border border-gray-300 shadow-sm">
        
        <div class="text-center mb-6 border-b-2 border-black pb-4">
            <h1 class="text-2xl font-bold uppercase tracking-wide text-gray-900">Laporan Stok Barang Gudang</h1>
            <p class="text-xs text-gray-600 mt-1">Sistem Manajemen Pergudangan (RusellStockify)</p>
            <p class="text-xs text-gray-500">Dibuat otomatis secara berkala • Tanggal Cetak: {{ now()->format('d-m-Y H:i') }} WIB</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-3 bg-gray-50 border border-gray-300 rounded text-center">
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Variasi Produk</p>
                <p class="text-lg font-bold text-gray-900">{{ $products->count() ?? '0' }} Item</p>
            </div>
            <div class="p-3 bg-gray-50 border border-gray-300 rounded text-center">
                <p class="text-xs text-gray-500 uppercase font-semibold">Total Akumulasi Stok</p>
                <p class="text-lg font-bold text-gray-900">{{ number_format($products->sum('stock'), 0, ',', '.') ?? '0' }} Unit</p>
            </div>
        </div>

        <table class="w-full text-left border-collapse border border-gray-400">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-400">
                    <th class="p-2.5 text-xs font-bold uppercase text-gray-800 border-r border-gray-400 text-center w-[15%]">ID</th>
                    <th class="p-2.5 text-xs font-bold uppercase text-gray-800 border-r border-gray-400 w-[45%]">Nama Produk</th>
                    <th class="p-2.5 text-xs font-bold uppercase text-gray-800 border-r border-gray-400 w-[25%]">Kategori</th>
                    <th class="p-2.5 text-xs font-bold uppercase text-gray-800 text-right w-[15%]">Stok Aktual</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse($products as $product)
                <tr class="border-b border-gray-300">
                    <td class="p-2.5 text-xs text-center font-mono text-gray-800 border-r border-gray-300">
                        #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="p-2.5 text-xs text-gray-900 font-medium border-r border-gray-300">
                        {{ $product->name }}
                    </td>
                    <td class="p-2.5 text-xs text-gray-800 border-r border-gray-300">
                        {{ $product->category->name ?? '-' }}
                    </td>
                    <td class="p-2.5 text-xs font-bold text-right text-gray-900">
                        {{ number_format($product->stock, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-xs text-gray-500 italic">Tidak ada data stok barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-12 flex justify-end text-sm">
            <div class="text-center w-48">
                <p class="text-xs text-gray-600 mb-16">Petugas Gudang,</p>
                <div class="border-b border-gray-600 w-full"></div>
                <p class="text-xs text-gray-500 mt-1">Administrator</p>
            </div>
        </div>

    </div>

</body>
</html>