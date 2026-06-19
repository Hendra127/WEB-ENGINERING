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

        PeminjamanTool::create([
            'alat_kantor_id' => null,
            'nama_alat' => 'FUSION SPLICER',
            'nama_peminjam' => 'MISDAN',
            'qty' => 1,
            'tgl_pinjam' => date('Y-m-d', strtotime('-7 days')),
            'tgl_kembali' => date('Y-m-d', strtotime('-1 days')),
            'status' => 'DIKEMBALI',
            'keterangan' => 'Splicing core di ruang server BKN'
        ]);
    }
}
