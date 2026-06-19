<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Pekerjaan - {{ $item->lokasi_pekerjaan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 30px;
            line-height: 1.5;
        }
        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-placeholder {
            width: 70px;
            height: 70px;
            background: #1e293b;
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
            margin-right: 20px;
        }
        .header-text {
            flex: 1;
        }
        .header-text h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-text p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        .doc-title {
            text-align: center;
            margin: 20px 0;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 16px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-title p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.details th, table.details td {
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        table.details th {
            width: 180px;
            background-color: #f8fafc;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            text-transform: uppercase;
        }
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        .photo-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            background: #fafafa;
        }
        .photo-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
            margin-bottom: 6px;
        }
        .photo-card .label {
            font-size: 11px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }
        .no-photo {
            height: 140px;
            background: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            border-radius: 4px;
            margin-bottom: 6px;
            border: 1px dashed #cbd5e1;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        .sig-block p.role {
            margin-bottom: 70px;
            font-weight: 500;
        }
        .sig-block p.name {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }
        .sig-block p.dept {
            font-size: 11px;
            color: #666;
            margin: 2px 0 0 0;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header-container">
        <div class="logo-placeholder">ENG</div>
        <div class="header-text">
            <h2>Management System - Engineering Department</h2>
            <p>Alamat Kantor: Divisi Engineering, Gedung Utama Lt. 2 | Telp: Ext. 404</p>
            <p>Email: engineering@company.com</p>
        </div>
    </div>

    <div class="doc-title">
        <h3>Berita Acara Pekerjaan & Serah Terima</h3>
        <p>No: BA/ENG/{{ date('Y') }}/{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="section-title">Detail Pekerjaan</div>
    <table class="details">
        <tr>
            <th>Lokasi Pekerjaan</th>
            <td><strong>{{ $item->lokasi_pekerjaan }}</strong></td>
        </tr>
        <tr>
            <th>Ruang / Area</th>
            <td>{{ $item->ruang ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Pekerjaan</th>
            <td>{{ $item->jenis_pekerjaan }}</td>
        </tr>
        <tr>
            <th>Type / Merk Perangkat</th>
            <td>{{ $item->type ?? '-' }}</td>
        </tr>
        <tr>
            <th>Teknisi Pelaksana</th>
            <td>
                @if(is_array($item->teknisi))
                    {{ implode(', ', $item->teknisi) }}
                @else
                    {{ $item->teknisi ?? '-' }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Waktu Pengerjaan</th>
            <td>
                Mulai: {{ $item->tgl_masuk ? $item->tgl_masuk->format('d-m-Y') : '-' }} 
                &nbsp;|&nbsp; 
                Selesai: {{ $item->tgl_selesai ? $item->tgl_selesai->format('d-m-Y') : '-' }}
            </td>
        </tr>
        <tr>
            <th>Deskripsi Kerusakan</th>
            <td>{{ $item->kerusakan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Tindakan Perbaikan</th>
            <td>{{ $item->action ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pergantian Perangkat</th>
            <td>{{ $item->pergantian_perangkat ?? 'Tidak ada pergantian' }} (Qty: {{ $item->qty }} {{ $item->satuan }})</td>
        </tr>
        @if($item->harga > 0)
        <tr>
            <th>Estimasi Biaya</th>
            <td>Rp {{ number_format($item->total_biaya, 0, ',', '.') }} (Rp {{ number_format($item->harga, 0, ',', '.') }} / {{ $item->satuan }})</td>
        </tr>
        @endif
        <tr>
            <th>Catatan Lainnya</th>
            <td>{{ $item->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Dokumentasi Kegiatan (Evidence)</div>
    <div class="photos-grid">
        <!-- Foto Masuk -->
        <div class="photo-card">
            @if($item->foto_masuk)
                <img src="{{ asset('storage/' . $item->foto_masuk) }}" alt="Foto Masuk">
            @else
                <div class="no-photo">Belum ada foto</div>
            @endif
            <div class="label">1. Foto Masuk / Awal</div>
        </div>

        <!-- Foto Proses -->
        <div class="photo-card">
            @if($item->foto_proses)
                <img src="{{ asset('storage/' . $item->foto_proses) }}" alt="Foto Proses">
            @else
                <div class="no-photo">Belum ada foto</div>
            @endif
            <div class="label">2. Foto Proses / Perbaikan</div>
        </div>

        <!-- Foto Keluar -->
        <div class="photo-card">
            @if($item->foto_keluar)
                <img src="{{ asset('storage/' . $item->foto_keluar) }}" alt="Foto Keluar">
            @else
                <div class="no-photo">Belum ada foto</div>
            @endif
            <div class="label">3. Foto Keluar / Selesai</div>
        </div>
    </div>

    <div class="signatures">
        <div class="sig-block">
            <p>Diserahkan Oleh,</p>
            <p class="role">Teknisi Engineering</p>
            <p class="name">
                @if(is_array($item->teknisi) && count($item->teknisi) > 0)
                    {{ $item->teknisi[0] }}
                @else
                    {{ is_string($item->teknisi) ? $item->teknisi : '___________________' }}
                @endif
            </p>
            <p class="dept">Divisi Engineering</p>
        </div>
        
        <div class="sig-block">
            <p>Diterima & Disetujui Oleh,</p>
            <p class="role">Pihak Lokasi / Penerima</p>
            <p class="name">___________________</p>
            <p class="dept">Staf Lokasi Kerja</p>
        </div>

        <div class="sig-block">
            <p>Mengetahui,</p>
            <p class="role">Manager Engineering</p>
            <p class="name">Hendra Hadi Pratama</p>
            <p class="dept">Dept. Head</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 24px; font-size: 14px; font-weight: bold; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer;">Cetak Berita Acara</button>
        <button onclick="window.close()" style="padding: 10px 24px; font-size: 14px; font-weight: bold; background: #fafafa; color: #333; border: 1px solid #ccc; border-radius: 6px; cursor: pointer; margin-left: 10px;">Tutup Halaman</button>
    </div>
</body>
</html>
