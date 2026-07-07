<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Barang</title>
    <style>
        /* --- Reset & Base Typography --- */
        @page {
            margin: 20mm 20mm 20mm 20mm;
        }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #0f172a; /* Slate 900 */
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* --- Header Section --- */
        .header-container {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        h2 { 
            text-align: left; 
            margin: 0 0 4px 0; 
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .meta-text { 
            text-align: left; 
            margin: 0; 
            font-size: 11px; 
            color: #64748b; /* Slate 500 */
        }

        /* --- Mini Summary Widgets (Biar Mirip Dashboard) --- */
        .summary-wrapper {
            margin-bottom: 24px;
            width: 100%;
        }
        .summary-card {
            display: inline-block;
            width: 30%;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px;
            margin-right: 3%;
            box-sizing: border-box;
        }
        .summary-card.last {
            margin-right: 0;
        }
        .summary-label {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        /* --- Modern Table Styling --- */
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 10px; 
        }
        th { 
            background-color: #0f172a; /* Tema Gelap Elegan untuk Header */
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border: none;
        }
        /* Membuat ujung header melengkung */
        th:first-child {
            border-top-left-radius: 8px;
        }
        th:last-child {
            border-top-right-radius: 8px;
        }
        
        td { 
            padding: 10px 14px; 
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 11px;
        }
        tr:nth-child(even) td { 
            background-color: #f8fafc; /* Zebra striping lembut */
        }

        /* --- Helper Utility Classes --- */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: Courier, monospace; font-weight: bold; color: #e11d48; }
        
        /* Badge Status untuk Stok */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            background-color: #f1f5f9;
            color: #334155;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <h2>Laporan Stok Barang Gudang</h2>
        <p class="meta-text">Dibuat otomatis secara berkala &bull; Tanggal Cetak: {{ now()->format('d-m-Y H:i') }} WIB</p>
    </div>

    <div class="summary-wrapper">
        <div class="summary-card">
            <div class="summary-label">Total Variasi Produk</div>
            <div class="summary-value">{{ $products->count() }} <span style="font-size: 10px; font-weight: normal; color: #94a3b8;">Item</span></div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Total Akumulasi Stok</div>
            <div class="summary-value">{{ $products->sum('minimum_stock') }} <span style="font-size: 10px; font-weight: normal; color: #94a3b8;">Unit</span></div>
        </div>
        <div class="summary-card last" style="background-color: #fff1f2; border-color: #ffe4e6;">
            <div class="summary-label" style="color: #be123c;">Status Sistem</div>
            <div class="summary-value" style="color: #be123c;">Aktif</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%" class="text-center">ID</th>
                <th width="45%">Nama Produk</th>
                <th width="25%">Kategori</th>
                <th width="20%" class="text-right">Stok Aktual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="text-center font-mono">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td style="font-weight: 600; color: #0f172a;">{{ $product->name }}</td>
                <td>
                    <span class="badge">
                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                    </span>
                </td>
                <td class="text-right" style="font-weight: 700; color: #0f172a;">
                    {{ number_format($product->minimum_stock, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>