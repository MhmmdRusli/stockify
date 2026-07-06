@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="grid grid-cols-1 w-full">
        <div class="text-left">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Dashboard Analitik</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan aktivitas transaksi dan status ketersediaan stok gudang.</p>
        </div>
    </div>
</div>

{{-- --- 1. WIDGET RINGKASAN DATA --- --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
    <div class="p-4 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Jenis Produk</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalProducts, 0, ',', '.') }} SKU</h3>
        </div>
        <div class="p-3 bg-blue-50 text-blue-700 rounded-lg dark:bg-gray-700 dark:text-blue-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Akumulasi Stok Masuk</p>
            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">+{{ number_format($totalFormatIn, 0, ',', '.') }} Pcs</h3>
        </div>
        <div class="p-3 bg-green-50 text-green-700 rounded-lg dark:bg-gray-700 dark:text-green-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Akumulasi Stok Keluar</p>
            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">-{{ number_format($totalFormatOut, 0, ',', '.') }} Pcs</h3>
        </div>
        <div class="p-3 bg-red-50 text-red-700 rounded-lg dark:bg-gray-700 dark:text-red-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
    {{-- --- 2. GRAFIK TRANSAKSI MINGGUAN --- --}}
    <div class="lg:col-span-2 p-4 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tren Transaksi 7 Hari Terakhir</h3>
        <div class="relative w-full h-64">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>

    {{-- --- 3. TABEL MONITORING STOK --- --}}
    <div class="p-4 bg-white rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Monitoring Batas Stok Terbaru</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Daftar produk aktif beserta ambang batas minimumnya.</p>
        
        <div class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="p-2.5 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">SKU / Nama</th>
                        <th class="p-2.5 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400 w-24">Batas Min</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($lowStockProducts as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="p-2.5 text-sm">
                            <span class="block font-mono font-bold text-gray-900 dark:text-white text-xs">{{ $product->sku ?? '-' }}</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 truncate max-w-[180px]">{{ $product->name }}</span>
                        </td>
                        <td class="p-2.5 text-right whitespace-nowrap">
                            <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-0.5 rounded dark:bg-orange-900/30 dark:text-orange-400">
                                {{ $product->minimum_stock }} Pcs
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="p-4 text-center text-xs text-gray-500 dark:text-gray-400">Belum ada data produk terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- --- 4. ENGINE SCRIPTS SINKRONISASI CHART.JS --- --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        
        const labels = @json($days);
        const dataIn = @json($dataIn);
        const dataOut = @json($dataOut);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk (In)',
                        data: dataIn,
                        backgroundColor: '#10b981', // Emerald Green
                        borderRadius: 4,
                    },
                    {
                        label: 'Barang Keluar (Out)',
                        data: dataOut,
                        backgroundColor: '#ef4444', // DIUBAH KE MERAH (Red Tailwind) agar serasi dengan widget
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#4b5563',
                            font: { family: 'Inter, sans-serif', size: 12 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { color: '#94a3b8', font: { size: 11 } },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection