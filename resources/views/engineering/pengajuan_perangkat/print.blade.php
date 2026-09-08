<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir {{ $item->nama_perangkat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #0f172a;
            margin: 0;
            padding: 15mm 20mm;
            background: #ffffff;
            line-height: 1.4;
        }

        /* Header Layout */
        .header-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
        }
        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-box img {
            height: 42px;
            object-fit: contain;
        }
        .logo-fallback {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo-icon {
            width: 38px;
            height: 38px;
        }
        .logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
            font-family: Arial, sans-serif;
        }
        .header-title-box {
            text-align: right;
        }
        .header-title-box h1 {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.25;
        }

        /* Header Divider Line */
        .header-divider {
            border: none;
            border-top: 2px solid #1e3a8a;
            margin: 8px 0 16px 0;
        }

        /* Meta Table */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 11px;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-table td.label-col {
            width: 120px;
            color: #1e293b;
        }
        .meta-table td.colon-col {
            width: 16px;
            text-align: left;
        }
        .meta-table td.val-col {
            color: #0f172a;
        }

        /* Letter Style for Pembelian Rumah Tangga */
        .letter-top-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .letter-left-meta {
            width: 60%;
        }
        .letter-left-meta table {
            border-collapse: collapse;
            font-size: 11px;
        }
        .letter-left-meta td {
            padding: 2px 0;
            vertical-align: top;
        }
        .letter-right-meta {
            width: 38%;
            text-align: right;
            font-size: 11px;
        }
        .letter-recipient-box {
            margin-top: 8px;
            margin-bottom: 14px;
            font-size: 11px;
            line-height: 1.5;
        }
        .letter-salutation {
            margin-bottom: 12px;
            font-size: 11px;
            font-weight: 500;
        }
        .letter-closing {
            margin-top: 14px;
            margin-bottom: 14px;
            font-size: 11px;
            line-height: 1.5;
        }

        /* Statement Text */
        .statement-text {
            font-size: 11px;
            margin-bottom: 12px;
            color: #0f172a;
            line-height: 1.4;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 10.5px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .items-table th {
            font-weight: bold;
            text-align: center;
            background-color: #ffffff;
            font-size: 10.5px;
        }
        .items-table td.center {
            text-align: center;
        }
        .items-table td.right {
            text-align: right;
        }
        .items-table td.bold {
            font-weight: bold;
        }
        .items-table .summary-row td {
            font-weight: bold;
        }

        /* Signatures Layout */
        .date-location {
            text-align: center;
            font-size: 11px;
            margin-top: 16px;
            margin-bottom: 24px;
            color: #0f172a;
        }

        .signatures-grid {
            width: 100%;
            margin-top: 5px;
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 26px;
        }
        .sig-box {
            width: 44%;
            text-align: center;
            position: relative;
        }
        .sig-box.center-box {
            width: 50%;
            margin: 0 auto;
        }
        .sig-role-title {
            font-size: 11px;
            margin-bottom: 4px;
            color: #0f172a;
        }
        .sig-space {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .sig-overlay-svg {
            max-height: 52px;
            max-width: 135px;
        }
        .sig-name {
            font-weight: bold;
            font-size: 11px;
            text-decoration: underline;
            margin-bottom: 2px;
            color: #0f172a;
        }
        .sig-position {
            font-size: 10.5px;
            color: #0f172a;
        }

        /* Print Controls */
        .no-print-bar {
            text-align: center;
            margin-top: 30px;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .btn-print-action {
            padding: 8px 20px;
            font-size: 12px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-print-primary {
            background: #1e3a8a;
            color: #ffffff;
            margin-right: 10px;
        }
        .btn-print-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        @media print {
            body {
                padding: 15mm 20mm;
                margin: 0;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body onload="window.print()">

    @php
        $details = $item->details ?? [];
        
        $rawTipe = $details['tipe_pengajuan'] ?? 'repair';
        $isPembelianRT = ($rawTipe === 'pembelian_rt' || $rawTipe === 'rumah_tangga');

        if (strtolower($rawTipe) === 'repair' || strtolower($rawTipe) === 'repair perangkat') {
            $tipeJudulLine1 = "FORMULIR PENGAJUAN";
            $tipeJudulLine2 = "REPAIR PERANGKAT";
            $tipeDisplay = "Repair Perangkat";
        } elseif ($isPembelianRT) {
            $tipeJudulLine1 = "PERMOHONAN PEMBELIAN";
            $tipeJudulLine2 = "PERALATAN RUMAH TANGGA";
            $tipeDisplay = "Pembelian Peralatan Rumah Tangga";
        } else {
            $tipeJudulLine1 = "FORMULIR PENGAJUAN";
            $tipeJudulLine2 = "PERANGKAT";
            $tipeDisplay = "Pengajuan Perangkat";
        }

        $tempat = $details['tempat'] ?? 'Mataram';
        
        // Format Tanggal Metadata
        if (!empty($details['tanggal'])) {
            $tglTime = strtotime($details['tanggal']);
            $tanggalMeta = date('d F Y', $tglTime);
        } else {
            $tanggalMeta = $item->created_at ? $item->created_at->format('d F Y') : date('d F Y');
        }

        // Format Tanggal TTD
        $tanggalTTD = date('d F Y', !empty($details['tanggal']) ? strtotime($details['tanggal']) : time());

        $divisi = $details['divisi'] ?? 'Manage Service AI BAKTI';
        $noPengajuan = !empty($details['no_pengajuan']) ? $details['no_pengajuan'] : '-';
        
        if ($isPembelianRT && (empty($details['keterangan_pengajuan']) || $details['keterangan_pengajuan'] === 'Dengan ini saya mengajukan perangkat sparepart untuk pergantian perangkat yang rusak dengan perincian sebagai berikut :')) {
            $keteranganPengajuan = 'Sehubungan dengan kebutuhan operasional dan sarana prasarana rumah tangga / mess, dengan ini kami mengajukan permohonan pembelian peralatan rumah tangga dengan perincian sebagai berikut:';
        } else {
            $keteranganPengajuan = $details['keterangan_pengajuan'] ?? 'Dengan ini saya mengajukan perangkat sparepart untuk pergantian perangkat yang rusak dengan perincian sebagai berikut :';
        }
        
        $itemsList = $details['items'] ?? [];
        $grandTotal = floatval($details['grand_total'] ?? 0);
        $terbilang = !empty($details['terbilang']) ? $details['terbilang'] : '';
        $catatan = !empty($details['catatan']) ? $details['catatan'] : '-';

        $tertanda = $details['tertanda'] ?? [];
        $pemohonNama = $tertanda['pemohon_nama'] ?? 'Lalu Taufik Wijaya';
        $pemohonJabatan = $tertanda['pemohon_jabatan'] ?? 'Engineering Leader';
        
        $verifi1Nama = $tertanda['verifikasi1_nama'] ?? 'Dimas Farid Awaludin, S.Kom';
        $verifi1Jabatan = $tertanda['verifikasi1_jabatan'] ?? 'Manager';

        $verifi2Nama = $tertanda['verifikasi2_nama'] ?? 'Baiq Nana Erlina, A.Md';
        $verifi2Jabatan = $tertanda['verifikasi2_jabatan'] ?? 'Accounting';

        $disetujuiNama = $tertanda['disetujui_nama'] ?? 'Galuh Zakiyatun, S.Kom';
        $disetujuiJabatan = $tertanda['disetujui_jabatan'] ?? 'Direktur';

        $mengetahuiNama = $tertanda['mengetahui_nama'] ?? 'Raden Yuniarta Alba, S.Kom';
        $mengetahuiJabatan = $tertanda['mengetahui_jabatan'] ?? 'Penasihat';
    @endphp

    <!-- Header Section -->
    <div class="header-wrap">
        <div class="logo-box">
            @if(file_exists(public_path('images/logo_nustech.png')))
                <img src="{{ asset('images/logo_nustech.png') }}" alt="NUSTECH">
            @elseif(file_exists(public_path('images/logo_nustech.jpg')))
                <img src="{{ asset('images/logo_nustech.jpg') }}" alt="NUSTECH">
            @else
                <div class="logo-fallback">
                    <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="50,5 90,25 90,75 50,95 10,75 10,25" stroke="#0284c7" stroke-width="8" fill="none" />
                        <circle cx="50" cy="50" r="14" fill="#0284c7" />
                        <line x1="30" y1="30" x2="70" y2="70" stroke="#0284c7" stroke-width="6" />
                        <line x1="70" y1="30" x2="30" y2="70" stroke="#0284c7" stroke-width="6" />
                    </svg>
                    <span class="logo-text">NUSTECH</span>
                </div>
            @endif
        </div>

        <div class="header-title-box">
            <h1>{{ $tipeJudulLine1 }}<br>{{ $tipeJudulLine2 }}</h1>
        </div>
    </div>

    <!-- Blue Line Divider -->
    <hr class="header-divider">

    @if($isPembelianRT)
    <!-- Letter Style Metadata & Recipient Header for Pembelian RT -->
    <div class="letter-top-meta">
        <div class="letter-left-meta">
            <table>
                <tr>
                    <td style="width: 70px;">Nomor</td>
                    <td style="width: 15px;">:</td>
                    <td>{{ $noPengajuan }}</td>
                </tr>
                <tr>
                    <td>Lamp.</td>
                    <td>:</td>
                    <td>1 Lembar</td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td><strong>Permohonan Pembelian Peralatan Rumah Tangga</strong></td>
                </tr>
            </table>
        </div>
        <div class="letter-right-meta">
            {{ $tempat }}, {{ $tanggalMeta }}
        </div>
    </div>

    <div class="letter-recipient-box">
        Kepada Yth.<br>
        <strong>Direktur & Management</strong><br>
        PT Nusa Network Prakarsa<br>
        Di _ Tempat.
    </div>

    <div class="letter-salutation">
        Assalamu 'Alaikum Wr. Wb. / Dengan hormat,
    </div>

    <div class="statement-text">
        {{ $keteranganPengajuan }}
    </div>
    @else
    <!-- Metadata Section -->
    <table class="meta-table">
        <tr>
            <td class="label-col">Tempat, Tanggal</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $tempat }}, {{ $tanggalMeta }}</td>
        </tr>
        <tr>
            <td class="label-col">Divisi / Bagian</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $divisi }}</td>
        </tr>
        <tr>
            <td class="label-col">No. Surat</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $noPengajuan }}</td>
        </tr>
        <tr>
            <td class="label-col">Tipe Pengajuan</td>
            <td class="colon-col">:</td>
            <td class="val-col">{{ $tipeDisplay }}</td>
        </tr>
    </table>

    <!-- Statement Paragraph -->
    <div class="statement-text">
        {{ $keteranganPengajuan }}
    </div>
    @endif

    <!-- Items Perincian Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th width="35">No.</th>
                <th>Perangkat</th>
                <th width="45">Qty</th>
                <th width="90">Harga</th>
                <th width="95">TOTAL</th>
                <th width="80">MITRA/KANTOR</th>
                <th width="95">Peruntukan</th>
                <th width="110">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($itemsList) && count($itemsList) > 0)
                @foreach($itemsList as $idx => $it)
                <tr>
                    <td class="center">{{ $idx + 1 }}.</td>
                    <td>{{ $it['perangkat'] ?? '-' }}</td>
                    <td class="center">{{ $it['qty'] ?? 1 }}</td>
                    <td class="center">Rp {{ number_format(floatval($it['harga_satuan'] ?? 0), 0, ',', '.') }}</td>
                    <td class="center bold">Rp {{ number_format(floatval($it['total'] ?? 0), 0, ',', '.') }}</td>
                    <td class="center">{{ $it['layanan'] ?? $it['mitra_kantor'] ?? '-' }}</td>
                    <td class="center">{{ $it['peruntukan'] ?? 'STOK' }}</td>
                    <td class="center">{{ $it['keterangan'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="center">1.</td>
                    <td>{{ $item->nama_perangkat }}</td>
                    <td class="center">1</td>
                    <td class="center">Rp 0</td>
                    <td class="center bold">Rp 0</td>
                    <td class="center">-</td>
                    <td class="center">STOK</td>
                    <td class="center">-</td>
                </tr>
            @endif

            <!-- Summary Rows -->
            <tr class="summary-row">
                <td colspan="4" class="center bold">TOTAL</td>
                <td class="center bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                <td colspan="3"></td>
            </tr>
            <tr class="summary-row">
                <td colspan="4" class="center bold">Terbilang</td>
                <td colspan="4">{{ $terbilang }}</td>
            </tr>
            <tr class="summary-row">
                <td colspan="4" class="center bold">Catatan</td>
                <td colspan="4">{{ $catatan }}</td>
            </tr>
        </tbody>
    </table>

    @if($isPembelianRT)
    <div class="letter-closing">
        Demikian permohonan ini kami sampaikan. Atas perhatian, bantuan, dan persetujuan Bapak/Ibu, kami ucapkan terima kasih.
    </div>
    @endif

    <!-- Location & Date above Signatures -->
    <div class="date-location">
        {{ $tempat }}, {{ $tanggalTTD }}
    </div>

    <!-- Signatures Section -->
    <div class="signatures-grid">
        <!-- Row 1: Pemohon & Diverifikasi 1 -->
        <div class="sig-row">
            <div class="sig-box">
                <div class="sig-role-title">Pemohon,</div>
                <div class="sig-space">
                    <!-- Handwritten signature SVG graphic matching Rossie signature -->
                    <svg class="sig-overlay-svg" viewBox="0 0 200 80" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 20 60 Q 40 10 65 35 T 100 25 Q 120 10 140 45 T 185 30" stroke="#000000" stroke-width="2.5" fill="none" stroke-linecap="round" />
                        <path d="M 30 45 Q 60 70 120 50 T 175 40" stroke="#000000" stroke-width="2" fill="none" stroke-linecap="round" />
                        <path d="M 50 25 L 145 65" stroke="#000000" stroke-width="2.2" fill="none" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="sig-name">{{ $pemohonNama }}</div>
                <div class="sig-position">{{ $pemohonJabatan }}</div>
            </div>

            <div class="sig-box">
                <div class="sig-role-title">Diverifikasi,</div>
                <div class="sig-space">
                    <!-- Handwritten signature SVG graphic matching Dimas signature -->
                    <svg class="sig-overlay-svg" viewBox="0 0 200 80" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 40 70 Q 30 15 70 20 T 100 65 Q 115 15 150 40 T 170 30" stroke="#000000" stroke-width="2.5" fill="none" stroke-linecap="round" />
                        <path d="M 60 30 C 90 10 130 50 155 20" stroke="#000000" stroke-width="2" fill="none" />
                        <line x1="85" y1="15" x2="85" y2="70" stroke="#000000" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="sig-name">{{ $verifi1Nama }}</div>
                <div class="sig-position">{{ $verifi1Jabatan }}</div>
            </div>
        </div>

        <!-- Row 2: Diverifikasi 2 & Disetujui -->
        <div class="sig-row">
            <div class="sig-box">
                <div class="sig-role-title">Diverifikasi,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $verifi2Nama }}</div>
                <div class="sig-position">{{ $verifi2Jabatan }}</div>
            </div>

            <div class="sig-box">
                <div class="sig-role-title">Disetujui,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $disetujuiNama }}</div>
                <div class="sig-position">{{ $disetujuiJabatan }}</div>
            </div>
        </div>

        <!-- Row 3: Mengetahui (Centered) -->
        <div class="sig-row" style="justify-content: center; margin-bottom: 0;">
            <div class="sig-box center-box">
                <div class="sig-role-title">Mengetahui,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $mengetahuiNama }}</div>
                <div class="sig-position">{{ $mengetahuiJabatan }}</div>
            </div>
        </div>
    </div>

    <!-- No-Print Action Buttons -->
    <div class="no-print-bar">
        <button class="btn-print-action btn-print-primary" onclick="window.print()">
            Cetak Dokumen
        </button>
        <button class="btn-print-action btn-print-secondary" onclick="window.close()">
            Tutup Halaman
        </button>
    </div>

</body>
</html>
