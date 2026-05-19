<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengajuan Perangkat - {{ $item->nama_perangkat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.details th, table.details td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        table.details th {
            width: 200px;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .footer .signature {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .footer .signature p {
            margin-bottom: 60px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>Detail Pengajuan Perangkat</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table class="details">
        <tr>
            <th>Pengusul</th>
            <td>{{ $item->user->name ?? '-' }} ({{ ucfirst($item->user->role ?? '') }})</td>
        </tr>
        <tr>
            <th>Nama Perangkat</th>
            <td>{{ $item->nama_perangkat }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>{{ $item->jumlah }}</td>
        </tr>
        <tr>
            <th>Alasan Pengajuan</th>
            <td>{{ $item->alasan }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($item->status == 'pending_manager')
                    Menunggu Manager
                @elseif($item->status == 'pending_accounting')
                    Menunggu Accounting
                @elseif($item->status == 'pending_direktur')
                    Menunggu Direktur
                @elseif($item->status == 'approved')
                    Disetujui
                @elseif($item->status == 'rejected')
                    Ditolak (Alasan: {{ $item->alasan_penolakan }})
                @else
                    {{ $item->status }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Tanggal Pengajuan</th>
            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,</p>
            <br><br>
            <strong>_____________________</strong>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">Tutup</button>
    </div>
</body>
</html>
