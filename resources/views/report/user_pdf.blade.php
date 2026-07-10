<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Log Aktivitas Transaksi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Georgia, 'Times New Roman', serif;
            color: #000;
            padding: 24px;
            font-size: 12px;
        }
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .print-header h1 { font-size: 18px; margin-bottom: 4px; }
        .print-header p { font-size: 11px; color: #333; }
        .print-meta { text-align: right; font-size: 11px; }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }
        .summary-box { border: 1px solid #94a3b8; padding: 8px 10px; }
        .summary-box .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #555;
            letter-spacing: 0.5px;
        }
        .summary-box .value { font-size: 15px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            font-size: 10.5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f1f5f9;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.5px;
        }
        td.text-right, th.text-right { text-align: right; }
        .badge-in { color: #047857; font-weight: bold; }
        .badge-out { color: #b91c1c; font-weight: bold; }
        .uid { font-size: 9px; color: #555; }
        .print-footer {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #444;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page { size: A4 landscape; margin: 14mm; }
        }
    </style>
</head>
<body>

    <div class="print-header">
        <div>
            <h1>Log Aktivitas Transaksi Pengguna</h1>
            <p>Rekam jejak aksi mutasi barang masuk dan keluar oleh staf gudang — Stockify Warehouse System</p>
        </div>
        <div class="print-meta">
            <p><strong>Dicetak pada:</strong> {{ now()->format('d M Y — H:i') }} WIB</p>
            <p><strong>Total Data:</strong> {{ $activities->count() }} log</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-box">
            <div class="label">Total Jejak Aksi</div>
            <div class="value">{{ $activities->count() }} Log</div>
        </div>
        <div class="summary-box">
            <div class="label">Mutasi Masuk</div>
            <div class="value">{{ $activities->where('type', 'in')->count() }} Aksi</div>
        </div>
        <div class="summary-box">
            <div class="label">Mutasi Keluar</div>
            <div class="value">{{ $activities->where('type', 'out')->count() }} Aksi</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Kontributor</div>
            <div class="value">{{ $activities->pluck('user_id')->unique()->count() }} User</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Waktu Eksekusi</th>
                <th style="width: 22%">Staf Eksekutor</th>
                <th style="width: 12%">Otoritas Role</th>
                <th style="width: 35%">Aktivitas & Target Produk</th>
                <th style="width: 16%" class="text-right">Volume Perubahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $act)
            <tr>
                <td>{{ $act->created_at->format('d M Y — H:i') }} WIB</td>
                <td>
                    {{ $act->user->name ?? 'Sistem Otomatis' }}<br>
                    <span class="uid">UID-{{ $act->user_id ?? '00' }}</span>
                </td>
                <td>{{ $act->user->role ?? 'System' }}</td>
                <td>
                    <span class="{{ $act->type === 'in' ? 'badge-in' : 'badge-out' }}">
                        [{{ $act->type === 'in' ? 'MASUK' : 'KELUAR' }}]
                    </span>
                    {{ $act->product->name ?? 'Produk Terhapus' }}
                </td>
                <td class="text-right">
                    <span class="{{ $act->type === 'in' ? 'badge-in' : 'badge-out' }}">
                        {{ $act->type === 'in' ? '+' : '-' }}{{ number_format($act->quantity, 0, ',', '.') }} Pcs
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding: 20px;">
                    Belum ada riwayat aktivitas log dari pengguna yang terdata.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="print-footer">
        <span>Stockify Warehouse Management System</span>
        <span>Dokumen ini digenerate otomatis oleh sistem.</span>
    </div>

    <script class="no-print">
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>