<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    
    <style>
        /* --- 📄 Pengaturan Dasar Halaman --- */
        * {
            box-sizing: border-box;
        }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 12px; 
            color: #1e293b; 
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }

        /* --- 📝 Kertas Dokumen Utama --- */
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* --- Kop Surat --- */
        .header-laporan {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .header-laporan h1 {
            font-size: 22px;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .header-laporan p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        /* --- 📦 Struktur Kotak Kubus Ke Samping (Simetris & Rapi) --- */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
            margin-left: -15px;
            margin-right: -15px;
            margin-bottom: 30px;
        }
        .summary-cell {
            width: 33.33%;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 22px 10px; /* Dipertebal agar bentuknya presisi kubus */
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .summary-cell .title {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 6px;
        }
        .summary-cell .value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }

        /* --- 📊 Tabel Utama Data Stok --- */
        .report-table { 
            width: 100%; 
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }
        .report-table th { 
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 10px;
            border: 1px solid #94a3b8;
            text-align: left;
        }
        .report-table td { 
            padding: 10px; 
            border: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
        }
        .report-table tr:nth-child(even) td { 
            background-color: #f8fafc;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; font-weight: bold; }

        /* --- Area Tanda Tangan --- */
        .signature-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-line {
            border-bottom: 1px solid #334155;
            display: inline-block;
            width: 180px;
            margin-top: 70px;
            margin-bottom: 4px;
        }

        /* --- Aturan Cetak PDF / Kertas --- */
        @media print {
            body { 
                background-color: white !important; 
                padding: 0 !important;
                font-family: 'Times New Roman', Times, serif;
            }
            .print-container {
                max-width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .report-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
        }
    </style>
</head>
<body>

    <div class="print-container">
        
        <div class="header-laporan">
            <h1>Laporan Stok Barang Gudang</h1>
            <p>Dibuat otomatis secara berkala &bull; Tanggal Cetak: {{ now()->format('d-m-Y H:i') }} WIB</p>
        </div>

        <table class="summary-table">
            <tr>
                <td class="summary-cell">
                    <div class="title">Total Variasi Produk</div>
                    <div class="value">{{ $products->count() }} <span style="font-size: 11px; font-weight: normal; color: #64748b;">Item</span></div>
                </td>
                <td class="summary-cell">
                    <div class="title">Total Akumulasi Stok</div>
                    <div class="value">{{ number_format($products->sum('minimum_stock'), 0, ',', '.') }} <span style="font-size: 11px; font-weight: normal; color: #64748b;">Unit</span></div>
                </td>
                <td class="summary-cell">
                    <div class="title">Status Sistem</div>
                    <div class="value" style="color: #10b981;">Aktif</div>
                </td>
            </tr>
        </table>

        <table class="report-table">
            <thead>
                <tr>
                    <th width="15%" class="text-center">ID</th>
                    <th width="45%">Nama Produk</th>
                    <th width="25%">Kategori</th>
                    <th width="15%" class="text-right">Stok Aktual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="text-center font-mono">
                        #{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="font-weight: bold; color: #0f172a;">{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'Tanpa Kategori' }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0f172a;">
                        {{ number_format($product->minimum_stock, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="signature-table">
            <tr>
                <td width="60%"></td>
                <td width="40%" class="text-center">
                    <p style="margin: 0; color: #1e293b;">Petugas Gudang,</p>
                    <div class="signature-line"></div>
                    <p style="margin: 0; font-size: 11px; color: #64748b;">Administrator</p>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>