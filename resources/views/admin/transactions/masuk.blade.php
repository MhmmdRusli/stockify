@extends('layouts.dashboard')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        @if(Auth::check() && Auth::user()->role === 'Manajer Gudang')
        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800 lg:col-span-1">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <span>🚚</span> Pencatatan Barang Masuk
            </h2>

            <form action="{{ route('barang.masuk.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>
                    <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah Kuantitas Masuk</label>
                    <input type="number" name="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required placeholder="Contoh: 50">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan / Keterangan</label>
                    <textarea name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: Restock dari Supplier A"></textarea>
                </div>

                <button type="submit" class="w-full text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none transition-colors">
                    Ajukan Barang Masuk
                </button>
            </form>
        </div>
        @endif

        <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800 {{ Auth::user()->role === 'Manajer Gudang' ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 flex items-center gap-2">
                <span>📋</span> Riwayat Transaksi Masuk
            </h3>
            
            <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Nama Produk</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Status / Aksi SOP</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $transaction->date }}</td>
                                <td class="px-4 py-4 font-medium text-gray-700 dark:text-gray-300">{{ $transaction->product->name ?? 'Produk Dihapus' }}</td>
                                <td class="px-4 py-4 text-green-600 font-bold">+{{ $transaction->quantity }}</td>
                                
                                <td class="px-4 py-4">
                                    @if($transaction->status === 'Pending')
                                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded dark:bg-yellow-950 dark:text-yellow-300 mb-1">Menunggu Konfirmasi</span>
                                        
                                        @if(Auth::check() && Auth::user()->role === 'Staff Gudang')
                                        <div class="flex space-x-1.5 mt-1">
                                            <form action="{{ route('transactions.konfirmasi', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?');">
                                                @csrf
                                                <button type="submit" class="text-white bg-green-500 hover:bg-green-600 focus:ring-2 focus:ring-green-300 font-medium rounded px-2.5 py-1 text-xs dark:bg-green-600 dark:hover:bg-green-700">Terima</button>
                                            </form>
                                            <form action="{{ route('transactions.tolak', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak transaksi ini?');">
                                                @csrf
                                                <button type="submit" class="text-white bg-red-500 hover:bg-red-600 focus:ring-2 focus:ring-red-300 font-medium rounded px-2.5 py-1 text-xs dark:bg-red-600 dark:hover:bg-red-700">Tolak</button>
                                            </form>
                                        </div>
                                        @endif

                                    @elseif($transaction->status === 'Diterima' || $transaction->status === 'Dikeluarkan')
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded dark:bg-green-950 dark:text-green-300">{{ $transaction->status }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded dark:bg-gray-700 dark:text-gray-300">Ditolak</span>
                                    @endif
                                </td>
                                
                                <td class="px-4 py-4 text-xs max-w-[150px] truncate" title="{{ $transaction->notes }}">{{ $transaction->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400 dark:text-gray-500">Belum ada riwayat barang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection