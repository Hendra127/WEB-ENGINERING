<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengajuan Perangkat - {{ $item->nama_perangkat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 25px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #15803d;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            color: #15803d;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 5px 8px;
            vertical-align: top;
        }
        .meta-table .label {
            font-weight: bold;
            width: 130px;
            color: #475569;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        table.items-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            text-align: center;
        }
        .total-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            margin-bottom: 25px;
            border-radius: 6px;
        }
        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 40px;
            text-align: center;
        }
        .sig-box {
            border: 1px solid #e2e8f0;
            padding: 10px 5px;
            border-radius: 6px;
            background: #fff;
        }
        .sig-title {
            font-weight: bold;
            font-size: 11px;
            color: #475569;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 50px;
        }
        .sig-name {
            font-weight: bold;
            font-size: 11px;
            color: #0f172a;
        }
        .sig-role {
            font-size: 10px;
            color: #64748b;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    @php
        $details = $item->details ?? [];
        $tipe = isset($details['tipe_pengajuan']) && $details['tipe_pengajuan'] === 'repair' ? 'Repair Perangkat' : 'Pembelian Baru (Stok)';
        $tempat = $details['tempat'] ?? 'Mataram';
        $tanggal = isset($details['tanggal']) ? date('d/m/Y', strtotime($details['tanggal'])) : $item->created_at->format('d/m/Y');
        $divisi = $details['divisi'] ?? 'Manage Service AI BAKTI';
        $noPengajuan = $details['no_pengajuan'] ?? '-';
        $itemsList = $details['items'] ?? [];
        $grandTotal = $details['grand_total'] ?? 0;
        $terbilang = $details['terbilang'] ?? '-';
        $tertanda = $details['tertanda'] ?? [];
    @endphp

    <div class="header">
        <h2>Form Pengajuan {{ $tipe }}</h2>
        <p>No: {{ $noPengajuan }} | Divisi: {{ $divisi }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Tipe Pengajuan:</td>
            <td><strong>{{ $tipe }}</strong></td>
            <td class="label">Tempat, Tanggal:</td>
            <td>{{ $tempat }}, {{ $tanggal }}</td>
        </tr>
        <tr>
            <td class="label">Pengusul:</td>
            <td>{{ $item->user->name ?? '-' }} ({{ ucfirst($item->user->role ?? 'Staff') }})</td>
            <td class="label">Status Approval:</td>
            <td><strong style="text-transform: uppercase; color:#15803d;">{{ str_replace('_', ' ', $item->status) }}</strong></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>PERANGKAT</th>
                <th width="50">QTY</th>
                <th width="110">HARGA SATUAN</th>
                <th width="110">TOTAL</th>
                <th>LAYANAN</th>
                <th>PERUNTUKAN</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($itemsList) && count($itemsList) > 0)
                @foreach($itemsList as $idx => $it)
                <tr>
                    <td style="text-align:center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $it['perangkat'] ?? '-' }}</strong></td>
                    <td style="text-align:center;">{{ $it['qty'] ?? 1 }}</td>
                    <td style="text-align:right;">Rp {{ number_format(floatval($it['harga_satuan'] ?? 0), 0, ',', '.') }}</td>
                    <td style="text-align:right; font-weight:bold;">Rp {{ number_format(floatval($it['total'] ?? 0), 0, ',', '.') }}</td>
                    <td>{{ $it['layanan'] ?? '-' }}</td>
                    <td>{{ $it['peruntukan'] ?? '-' }}</td>
                    <td>{{ $it['keterangan'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td style="text-align:center;">1</td>
                    <td><strong>{{ $item->nama_perangkat }}</strong></td>
                    <td style="text-align:center;">{{ $item->jumlah }}</td>
                    <td style="text-align:right;">-</td>
                    <td style="text-align:right; font-weight:bold;">-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{ $item->alasan }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="total-box">
        <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:bold;">
            <span>Grand Total:</span>
            <span style="color:#15803d;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>
        <div style="margin-top:6px; font-size:11px; color:#475569;">
            <strong>Terbilang:</strong> <em>{{ $terbilang }}</em>
        </div>
    </div>

    <!-- Signatures section matching 5 signers -->
    <div class="signatures-grid">
        <div class="sig-box">
            <div class="sig-title">Pemohon</div>
            <div class="sig-name">{{ $tertanda['pemohon_nama'] ?? 'Rossie Maulana Septian, S.Kom' }}</div>
            <div class="sig-role">{{ $tertanda['pemohon_jabatan'] ?? 'NOC Leader' }}</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Diverifikasi 1</div>
            <div class="sig-name">{{ $tertanda['verifikasi1_nama'] ?? 'Dimas Farid Awaludin, S.Kom' }}</div>
            <div class="sig-role">{{ $tertanda['verifikasi1_jabatan'] ?? 'Manager' }}</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Diverifikasi 2</div>
            <div class="sig-name">{{ $tertanda['verifikasi2_nama'] ?? 'Baiq Nana Erlina, A.Md' }}</div>
            <div class="sig-role">{{ $tertanda['verifikasi2_jabatan'] ?? 'Accounting' }}</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Disetujui</div>
            <div class="sig-name">{{ $tertanda['disetujui_nama'] ?? 'Galuh Zakiyatun, S.Kom' }}</div>
            <div class="sig-role">{{ $tertanda['disetujui_jabatan'] ?? 'Direktur' }}</div>
        </div>
        <div class="sig-box">
            <div class="sig-title">Mengetahui</div>
            <div class="sig-name">{{ $tertanda['mengetahui_nama'] ?? 'Raden Yuniarta Alba, S.Kom' }}</div>
            <div class="sig-role">{{ $tertanda['mengetahui_jabatan'] ?? 'Penasihat' }}</div>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 8px 18px; background:#15803d; color:#fff; border:none; border-radius:4px; font-weight:bold; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 8px 18px; background:#e2e8f0; color:#333; border:none; border-radius:4px; font-weight:bold; cursor: pointer; margin-left: 10px;">Tutup</button>
    </div>

</body>
</html>
