@extends('layouts.dashboard')

@section('content')
<div class="p-4 bg-white border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between gap-4 w-full">
        
        <div class="text-left">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white whitespace-nowrap">Riwayat Transaksi Stok</h1>
            
            @if(session('success'))
                <div class="mt-2 p-2 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 inline-block" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mt-2 p-2 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 inline-block" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <div class="flex items-center gap-2 justify-end shrink-0">
            <a href="{{ route('transactions.print') }}" target="_blank" class="inline-flex items-center justify-center text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Laporan
            </a>

            <button type="button" data-modal-target="add-transaction-modal" data-modal-toggle="add-transaction-modal" class="inline-flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-colors duration-200 whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Catat Transaksi Stok
            </button>
        </div>

    </div>
</div>

<div class="flex flex-col mt-4">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Tanggal & Waktu</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">SKU</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Nama Produk</th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">Tipe</th>
                            <th scope="col" class="p-4 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Jumlah</th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-4 text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $transaction->product->sku ?? '-' }}</td>
                            <td class="p-4 text-sm font-medium text-gray-900 dark:text-white">{{ $transaction->product->name ?? 'Produk Terhapus' }}</td>
                            <td class="p-4 text-center whitespace-nowrap">
                                @if(strtolower($transaction->type) === 'in')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded dark:bg-green-900 dark:text-green-300 uppercase tracking-wide">Masuk (In)</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded dark:bg-red-900 dark:text-red-300 uppercase tracking-wide">Keluar (Out)</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm font-bold text-right whitespace-nowrap {{ strtolower($transaction->type) === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ strtolower($transaction->type) === 'in' ? '+' : '-' }} {{ number_format($transaction->quantity, 0, ',', '.') }}
                            </td>
                            {{-- TAMBAHAN KOLOM STATUS --}}
                            <td class="p-4 text-center whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $transaction->status ?? 'Selesai' }}
                                </span>
                            </td>
                            <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $transaction->notes }}">{{ $transaction->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada riwayat transaksi stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add-transaction-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Pencatatan Mutasi Stok</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="add-transaction-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>
                        <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled selected>-- Pilih Produk Anda --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{-- DISESUAIKAN: Menghilangkan teks "Stok: minimum_stock" agar tidak membingungkan --}}
                                    {{ $product->sku ?? 'No SKU' }} - {{ $product->name }} (Min: {{ $product->minimum_stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Transaksi</label>
                        <div class="flex items-center space-x-6 bg-gray-50 dark:bg-gray-600 p-2.5 rounded-lg border border-gray-300 dark:border-gray-500">
                            <label class="flex items-center text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                <input type="radio" name="type" value="in" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                                <span class="ml-2 text-green-600 dark:text-green-400 font-bold">Stok Masuk (IN)</span>
                            </label>
                            <label class="flex items-center text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                <input type="radio" name="type" value="out" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-red-600 dark:text-red-400 font-bold">Stok Keluar (OUT)</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah (Quantity)</label>
                        <input type="number" name="quantity" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="10" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan / Catatan</label>
                        <textarea name="notes" rows="2" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Contoh: Restock bulanan dari supplier / Barang display rusak"></textarea>
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors duration-200">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection