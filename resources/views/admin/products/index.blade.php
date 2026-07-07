@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">

    <div class="bg-white border border-gray-100 dark:bg-gray-900 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <div class="border-b border-gray-100 dark:border-gray-800 pb-4 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-950 dark:text-white">
                    Manajemen Data Produk
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Kelola stok, harga, dan informasi produk gudang Anda.
                </p>
            </div>
            
            {{-- TOMBOL TAMBAH MANUAL (Pindah ke Atas Kanan biar Sejajar Judul) --}}
            @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
            <div>
                <button type="button" data-modal-target="add-product-modal" data-modal-toggle="add-product-modal" class="inline-flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-xs px-4 py-2.5 shadow-md shadow-indigo-100 dark:shadow-none transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Tambah Produk Manual
                </button>
            </div>
            @endif
        </div>
        
        @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-gray-50 dark:bg-gray-800/40 p-3 rounded-xl border border-gray-200/50 dark:border-gray-700/50">
            <div class="flex items-center gap-2">
                <span class="p-1.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </span>
                <div>
                    <h2 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Import Massal</h2>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Unggah format file .xlsx</p>
                </div>
            </div>
            
            <form action="{{ route('report.stock.import') }}" method="POST" enctype="multipart/form-data" class="w-full sm:w-auto flex items-center gap-2">
                @csrf
                <input type="file" name="file" required class="block text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 focus:outline-none p-1 max-w-[200px] sm:max-w-xs">
                <button type="submit" class="inline-flex items-center justify-center text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-2 transition-colors">
                    Unggah
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="flex items-center p-3 text-xs text-green-800 border border-green-100 rounded-xl bg-green-50 dark:bg-gray-800/30 dark:text-green-400 dark:border-green-900/30" role="alert">
            <svg class="flex-shrink-0 inline w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <div><span class="font-bold">Sukses!</span> {{ session('success') }}</div>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="flex p-3 text-xs text-red-800 border border-red-100 rounded-xl bg-red-50 dark:bg-gray-800/30 dark:text-red-400 dark:border-red-900/30" role="alert">
            <svg class="flex-shrink-0 inline w-3.5 h-3.5 mr-2 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm1 4v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 2 0Zm-1 10a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
            </svg>
            <div>
                <span class="font-bold">Terjadi kesalahan input:</span>
                <ul class="list-disc pl-4 mt-0.5 space-y-0.5 text-[11px]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-[12%]">SKU</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-[23%]">Nama Produk</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-[15%]">Kategori</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-[15%]">Supplier</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-right text-gray-500 dark:text-gray-400 w-[11%]">Harga Beli</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-right text-gray-500 dark:text-gray-400 w-[11%]">Harga Jual</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-center text-gray-500 dark:text-gray-400 w-[8%]">Min. Stok</th>
                        @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-center text-gray-500 dark:text-gray-400 w-[15%]">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-sm font-mono font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">{{ $product->sku }}</td>
                        <td class="p-4 text-sm font-semibold text-gray-900 dark:text-white break-words">{{ $product->name }}</td>
                        <td class="p-4 text-sm whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-1 rounded-md dark:bg-gray-800 dark:text-gray-300">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500 dark:text-gray-400 truncate max-w-[150px]">{{ $product->supplier->name ?? '-' }}</td>
                        <td class="p-4 text-sm font-medium text-gray-900 dark:text-white text-right whitespace-nowrap">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="p-4 text-sm font-medium text-emerald-600 dark:text-emerald-400 text-right whitespace-nowrap">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-100/60 dark:border-amber-900/30">
                                {{ $product->minimum_stock }}
                            </span>
                        </td>
                        
                        @if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
                        <td class="p-4 text-center whitespace-nowrap space-x-1">
                            <button type="button" data-modal-target="edit-product-modal-{{ $product->id }}" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="text-amber-700 bg-amber-50 hover:bg-amber-100 font-semibold rounded-xl text-xs px-3 py-2 border border-amber-200/40 dark:bg-amber-950/20 dark:text-amber-400 dark:hover:bg-amber-950/50 dark:border-amber-900/30 transition-all">
                                Edit
                            </button>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus produk ini?')" class="text-red-700 bg-red-50 hover:bg-red-100 font-semibold rounded-xl text-xs px-3 py-2 border border-red-200/40 dark:bg-red-950/20 dark:text-red-400 dark:hover:bg-red-950/50 dark:border-red-900/30 transition-all">
                                    Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::check() && Auth::user()->role === 'Staff Gudang' ? '7' : '8' }}" class="p-12 text-center text-gray-400 dark:text-gray-500 text-sm">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                            </svg>
                            Belum ada data produk yang terdaftar di sistem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->role !== 'Staff Gudang')
@foreach($products as $product)
<div id="edit-product-modal-{{ $product->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-950/40">
    <div class="relative w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-950 dark:text-white">
                    Edit Produk: <span class="text-indigo-600 dark:text-indigo-400 font-mono">{{ $product->sku }}</span>
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-700" data-modal-toggle="edit-product-modal-{{ $product->id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('products.update', $product->id) }}" method="POST" class="p-5">
                @csrf
                @method('PUT')
                
                <div class="grid gap-4 mb-5 grid-cols-1 sm:grid-cols-2 text-left">
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Kategori</label>
                        <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Supplier</label>
                        <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" value="{{ $product->selling_price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Minimum Stok</label>
                        <input type="number" name="minimum_stock" value="{{ $product->minimum_stock }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    </div>
                    
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-400 dark:text-gray-500">SKU (Permanen)</label>
                        <input type="text" name="sku" value="{{ $product->sku }}" class="bg-gray-100 border border-gray-200 text-gray-400 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500 cursor-not-allowed font-mono" readonly>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-2 border-t pt-4 border-gray-100 dark:border-gray-700 rounded-b">
                    <button type="button" data-modal-toggle="edit-product-modal-{{ $product->id }}" class="text-gray-500 bg-white hover:bg-gray-100 rounded-xl border border-gray-200 text-sm font-semibold px-4 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-4 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div id="add-product-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-950/40">
    <div class="relative w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-base font-bold text-gray-950 dark:text-white">Tambah Produk Baru</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-700" data-modal-toggle="add-product-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('products.store') }}" method="POST" class="p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-1 sm:grid-cols-2 text-left">
                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Produk</label>
                        <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: Kulkas Sharp 2 Pintu" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Kategori</label>
                        <select name="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Supplier</label>
                        <select name="supplier_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Harga Beli</label>
                        <input type="number" name="purchase_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="3000000" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Harga Jual</label>
                        <input type="number" name="selling_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="3500000" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">Minimum Stok</label>
                        <input type="number" name="minimum_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="5" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-700 dark:text-gray-300">SKU (Opsional)</label>
                        <input type="text" name="sku" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono" placeholder="Otomatis jika kosong">
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-2 border-t pt-4 border-gray-100 dark:border-gray-700 rounded-b">
                    <button type="button" data-modal-toggle="add-product-modal" class="text-gray-500 bg-white hover:bg-gray-100 rounded-xl border border-gray-200 text-sm font-semibold px-4 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-xl text-sm px-5 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection 