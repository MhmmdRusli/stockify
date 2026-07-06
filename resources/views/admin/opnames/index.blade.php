@extends('layouts.dashboard')

@section('content')
    <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="mb-4">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Riwayat Stock Opname</h1>
            </div>
            <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700">
                <div class="flex items-center mb-4 sm:mb-0">
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <button type="button" data-modal-target="add-opname-modal" data-modal-toggle="add-opname-modal"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Catat Opname Baru
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Tanggal</th>
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Produk</th>
                                <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">Jenis</th>
                                <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">Rincian Stok</th>
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Keterangan</th>
                                <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($opnames as $opname)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ $opname->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-4 text-base font-semibold text-gray-900 dark:text-white">
                                        <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $opname->product->name }}</div>
                                        <div class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $opname->product->sku }}</div>
                                    </td>
                                    
                                    <td class="p-4 text-center whitespace-nowrap">
                                        @if ($opname->physical_stock > $opname->system_stock)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Stok Masuk</span>
                                        @elseif($opname->physical_stock < $opname->system_stock)
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Stok Keluar</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Sesuai</span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-center text-sm font-semibold whitespace-nowrap">
                                        <div class="text-gray-900 dark:text-white">Fisik: {{ $opname->physical_stock }}</div>
                                        <div class="text-xs text-gray-500">Sistem: {{ $opname->system_stock }}</div>
                                    </td>

                                    <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                        {{ $opname->notes ?? '-' }}
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-900 dark:text-white">
                                        {{ $opname->user->name ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat stock opname.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="add-opname-modal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Catat Opname Stok</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="add-opname-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('opnames.store') }}" method="POST" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk</label>
                            <select name="product_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" required>
                                <option value="" disabled selected>-- Pilih Produk yang di-Opname --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Sistem</label>
                            <input type="number" name="system_stock" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Stok di komputer" required>
                        </div>

                        <div class="col-span-2 sm:col-span-1">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok Fisik</label>
                            <input type="number" name="physical_stock" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Stok nyata di gudang" required>
                        </div>

                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan / Alasan</label>
                            <textarea name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white" placeholder="Contoh: Selisih hitung, barang rusak, dll..."></textarea>
                        </div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                        Simpan Log Opname
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection