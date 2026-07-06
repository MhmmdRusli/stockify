<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Stok</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    {{-- UBAH BAGIAN STYLE INI --}}
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white; }
            
            /* Tambahkan baris ini agar warna hijau/merah Tailwind tidak hilang saat dicetak */
            * { 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
        }
    </style>
</head> 
<body class="bg-gray-50 p-8 text-gray-900">

    <div class="no-print mb-6 flex justify-between items-center bg-blue-50 p-4 rounded-lg border border-blue-200">
        <span class="text-sm text-blue-800 font-medium">Pratinjau Laporan Cetak. Silakan klik tombol di kanan untuk mencetak atau simpan PDF.</span>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg shadow cursor-pointer">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg cursor-pointer">
                Tutup Halaman
            </button>
        </div>
    </div>

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="text-center mb-8 border-b pb-4 border-gray-200">
            <h1 class="text-2xl font-bold uppercase tracking-wide text-gray-800">Laporan Mutasi & Transaksi Stok</h1>
            <p class="text-sm text-gray-500 mt-1">Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-300 bg-gray-100">
                    <th class="p-3 text-sm font-bold uppercase text-gray-700">Tanggal & Waktu</th>
                    <th class="p-3 text-sm font-bold uppercase text-gray-700">SKU</th>
                    <th class="p-3 text-sm font-bold uppercase text-gray-700">Nama Produk</th>
                    <th class="p-3 text-sm font-bold uppercase text-gray-700 text-center">Tipe</th>
                    <th class="p-3 text-sm font-bold uppercase text-gray-700 text-right">Jumlah</th>
                    {{-- TAMBAHKAN JUDUL KOLOM STATUS DI SINI --}}
                    <th class="p-3 text-sm font-bold uppercase text-gray-700 text-center">Status</th>
                    <th class="p-3 text-sm font-bold uppercase text-gray-700">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr>
                    <td class="p-3 text-sm text-gray-600">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                    <td class="p-3 text-sm font-mono font-bold text-gray-800">{{ $transaction->product->sku ?? '-' }}</td>
                    <td class="p-3 text-sm text-gray-800">{{ $transaction->product->name ?? 'Produk Terhapus' }}</td>
                    <td class="p-3 text-sm text-center">
                        <span class="font-semibold {{ strtolower($transaction->type) === 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ strtolower($transaction->type) === 'in' ? 'MASUK' : 'KELUAR' }}
                        </span>
                    </td>
                    <td class="p-3 text-sm font-bold text-right {{ strtolower($transaction->type) === 'in' ? 'text-green-600' : 'text-red-600' }}">
                        {{ strtolower($transaction->type) === 'in' ? '+' : '-' }} {{ number_format($transaction->quantity, 0, ',', '.') }}
                    </td>
                    {{-- TAMBAHKAN ISI DATA STATUS DI SINI --}}
                    <td class="p-3 text-sm text-center text-gray-700 font-medium whitespace-nowrap">
                        {{ $transaction->status ?? 'Selesai' }}
                    </td>
                    <td class="p-3 text-sm text-gray-600">{{ $transaction->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    {{-- JANGAN LUPA COLSPAN DIUBAH JADI 7 KARENA KOLOM BERTABAH --}}
                    <td colspan="7" class="p-6 text-center text-sm text-gray-400">Tidak ada data transaksi mutasi stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>