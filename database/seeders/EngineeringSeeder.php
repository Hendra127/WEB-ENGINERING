<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\SparepartNeeded;
use App\Models\AlatKantor;
use App\Models\KlasifikasiBarang;

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
    }
}
