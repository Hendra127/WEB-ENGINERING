<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\SparepartNeeded;
use App\Models\AlatKantor;
use App\Models\KlasifikasiBarang;
use App\Models\PeminjamanTool;

class EngineeringSeeder extends Seeder
{
    public function run(): void
    {
        // Spareparts
        SparepartNeeded::create([
            'lokasi_pekerjaan' => 'DKN PROVINSI NTB',
            'ruang' => 'Admin',
            'jenis_pekerjaan' => 'PRINTER',
            'type' => 'EPSON S1110',
            'qty' => 1,
            'satuan' => 'Unit',
            'teknisi' => ['ANDI PRATAMA', 'MISDAN'],
            'tgl_masuk' => '2024-03-10',

            'tgl_selesai' => '2024-03-15',
            'keterangan' => 'Maintenance printer & service',
            'status' => 'DONE'
        ]);

        // Alat Kantor
        AlatKantor::create([
            'nama_tool' => 'TANGGA LIPAT',
            'qty' => 1,
            'satuan' => 'UNIT',
            'kondisi' => 'BAIK',
            'tempat' => 'KANTOR'
        ]);
        AlatKantor::create([
            'nama_tool' => 'KOMPRESOR ANGIN',
            'qty' => 1,
            'satuan' => 'UNIT',
            'kondisi' => 'BAIK',
            'tempat' => 'KANTOR'
        ]);
        AlatKantor::create([
            'nama_tool' => 'FOTT',
            'qty' => 1,
            'satuan' => 'UNIT',
            'kondisi' => 'BAIK',
            'tempat' => 'KANTOR'
        ]);

        // Klasifikasi
        KlasifikasiBarang::create([
            'tgl_masuk' => '2024-01-10',
            'tgl_keluar' => '2024-01-15',
            'nama_barang' => 'KABEL UTP CAT6',
            'qty' => 5,
            'satuan' => 'Roll',
            'nama_penerima' => 'ANDI PRATAMA',
            'lokasi' => 'Server Room',
            'status' => 'KELUAR'
        ]);

        // Peminjaman Tools
        $tangga = AlatKantor::where('nama_tool', 'TANGGA LIPAT')->first();
        PeminjamanTool::create([
            'alat_kantor_id' => $tangga ? $tangga->id : null,
            'nama_alat' => $tangga ? $tangga->nama_tool : 'TANGGA LIPAT',
            'nama_peminjam' => 'ANDI PRATAMA',
            'qty' => 1,
            'tgl_pinjam' => date('Y-m-d'),
            'status' => 'DIPINJAM',
            'keterangan' => 'Pekerjaan perbaikan kabel di DKN Provinsi NTB'
        ]);

        // Pengajuan Perangkat Seed Data
        \App\Models\PengajuanPerangkat::create([
            'user_id' => 1,
            'nama_perangkat' => 'POE 48v (5), POE 24v (3), ADAPTOR MIKROTIK (1)',
            'jumlah' => '9 Unit',
            'alasan' => 'No: 001/SP/2026 (Manage Service AI BAKTI)',
            'status' => 'pending_penasihat',
            'details' => [
                'tipe_pengajuan' => 'pembelian',
                'tempat' => 'Mataram',
                'tanggal' => '2026-08-01',
                'divisi' => 'Manage Service AI BAKTI',
                'no_pengajuan' => '001/SP/2026',
                'grand_total' => 550000,
                'status_pembayaran' => 'Belum Dibayar',
                'terbilang' => 'Lima Ratus Lima Puluh Ribu Rupiah',
                'items' => [
                    ['perangkat' => 'POE 48v', 'qty' => 5, 'harga_satuan' => 60000, 'total' => 300000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-'],
                    ['perangkat' => 'POE 24v', 'qty' => 3, 'harga_satuan' => 50000, 'total' => 150000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-'],
                    ['perangkat' => 'ADAPTOR MIKROTIK', 'qty' => 1, 'harga_satuan' => 100000, 'total' => 100000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-']
                ],
                'tertanda' => [
                    'pemohon_nama' => 'Rossie Maulana Septian, S.Kom', 'pemohon_jabatan' => 'NOC Leader',
                    'verifikasi1_nama' => 'Dimas Farid Awaludin, S.Kom', 'verifikasi1_jabatan' => 'Manager',
                    'verifikasi2_nama' => 'Baiq Nana Erlina, A.Md', 'verifikasi2_jabatan' => 'Accounting',
                    'disetujui_nama' => 'Galuh Zakiyatun, S.Kom', 'disetujui_jabatan' => 'Direktur',
                    'mengetahui_nama' => 'Raden Yuniarta Alba, S.Kom', 'mengetahui_jabatan' => 'Penasihat'
                ]
            ]
        ]);

        \App\Models\PengajuanPerangkat::create([
            'user_id' => 1,
            'nama_perangkat' => 'VOLTECH VT 601 (1)',
            'jumlah' => '1 Unit',
            'alasan' => 'No: 002/SP/2026 (Manage Service AI BAKTI)',
            'status' => 'pending_direktur',
            'details' => [
                'tipe_pengajuan' => 'pembelian',
                'tempat' => 'Mataram',
                'tanggal' => '2026-07-23',
                'divisi' => 'Manage Service AI BAKTI',
                'no_pengajuan' => '002/SP/2026',
                'grand_total' => 1050000,
                'status_pembayaran' => 'Lunas',
                'terbilang' => 'Satu Juta Lima Puluh Ribu Rupiah',
                'items' => [
                    ['perangkat' => 'VOLTECH VT 601', 'qty' => 1, 'harga_satuan' => 1050000, 'total' => 1050000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => 'Unit Utama']
                ],
                'tertanda' => [
                    'pemohon_nama' => 'Rossie Maulana Septian, S.Kom', 'pemohon_jabatan' => 'NOC Leader',
                    'verifikasi1_nama' => 'Dimas Farid Awaludin, S.Kom', 'verifikasi1_jabatan' => 'Manager',
                    'verifikasi2_nama' => 'Baiq Nana Erlina, A.Md', 'verifikasi2_jabatan' => 'Accounting',
                    'disetujui_nama' => 'Galuh Zakiyatun, S.Kom', 'disetujui_jabatan' => 'Direktur',
                    'mengetahui_nama' => 'Raden Yuniarta Alba, S.Kom', 'mengetahui_jabatan' => 'Penasihat'
                ]
            ]
        ]);

        \App\Models\PengajuanPerangkat::create([
            'user_id' => 1,
            'nama_perangkat' => 'UAP AC M (1), UAP AC M (1)',
            'jumlah' => '2 Unit',
            'alasan' => 'No: 003/SP/2026 (Manage Service AI BAKTI)',
            'status' => 'pending_direktur',
            'details' => [
                'tipe_pengajuan' => 'pembelian',
                'tempat' => 'Mataram',
                'tanggal' => '2026-07-21',
                'divisi' => 'Manage Service AI BAKTI',
                'no_pengajuan' => '003/SP/2026',
                'grand_total' => 1649999,
                'status_pembayaran' => 'Lunas',
                'terbilang' => 'Satu Juta Enam Ratus Empat Puluh Sembilan Ribu Sembilan Ratus Sembilan Puluh Sembilan Rupiah',
                'items' => [
                    ['perangkat' => 'UAP AC M', 'qty' => 1, 'harga_satuan' => 825000, 'total' => 825000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-'],
                    ['perangkat' => 'UAP AC M', 'qty' => 1, 'harga_satuan' => 824999, 'total' => 824999, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-']
                ],
                'tertanda' => [
                    'pemohon_nama' => 'Rossie Maulana Septian, S.Kom', 'pemohon_jabatan' => 'NOC Leader',
                    'verifikasi1_nama' => 'Dimas Farid Awaludin, S.Kom', 'verifikasi1_jabatan' => 'Manager',
                    'verifikasi2_nama' => 'Baiq Nana Erlina, A.Md', 'verifikasi2_jabatan' => 'Accounting',
                    'disetujui_nama' => 'Galuh Zakiyatun, S.Kom', 'disetujui_jabatan' => 'Direktur',
                    'mengetahui_nama' => 'Raden Yuniarta Alba, S.Kom', 'mengetahui_jabatan' => 'Penasihat'
                ]
            ]
        ]);

        \App\Models\PengajuanPerangkat::create([
            'user_id' => 1,
            'nama_perangkat' => 'KABEL LAN PER METER (60), KONEKTOR BELDEN (10)',
            'jumlah' => '70 Pcs',
            'alasan' => 'No: 004/SP/2026 (Manage Service AI BAKTI)',
            'status' => 'pending_penasihat',
            'details' => [
                'tipe_pengajuan' => 'pembelian',
                'tempat' => 'Mataram',
                'tanggal' => '2026-07-17',
                'divisi' => 'Manage Service AI BAKTI',
                'no_pengajuan' => '004/SP/2026',
                'grand_total' => 870000,
                'status_pembayaran' => 'Lunas',
                'terbilang' => 'Delapan Ratus Tujuh Puluh Ribu Rupiah',
                'items' => [
                    ['perangkat' => 'KABEL LAN PER METER', 'qty' => 60, 'harga_satuan' => 12000, 'total' => 720000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-'],
                    ['perangkat' => 'KONEKTOR BELDEN', 'qty' => 10, 'harga_satuan' => 15000, 'total' => 150000, 'layanan' => 'BMN', 'peruntukan' => 'STOK', 'keterangan' => '-']
                ],
                'tertanda' => [
                    'pemohon_nama' => 'Rossie Maulana Septian, S.Kom', 'pemohon_jabatan' => 'NOC Leader',
                    'verifikasi1_nama' => 'Dimas Farid Awaludin, S.Kom', 'verifikasi1_jabatan' => 'Manager',
                    'verifikasi2_nama' => 'Baiq Nana Erlina, A.Md', 'verifikasi2_jabatan' => 'Accounting',
                    'disetujui_nama' => 'Galuh Zakiyatun, S.Kom', 'disetujui_jabatan' => 'Direktur',
                    'mengetahui_nama' => 'Raden Yuniarta Alba, S.Kom', 'mengetahui_jabatan' => 'Penasihat'
                ]
            ]
        ]);
    }
}
