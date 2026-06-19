<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SparepartNeeded extends Model {
    protected $table = 'sparepart_needed';
    protected $fillable = ['lokasi_pekerjaan','ruang','jenis_pekerjaan','type','qty','satuan','teknisi','tgl_masuk','tgl_selesai','kerusakan','action','keterangan','status','pergantian_perangkat','keterangan_tambahan','harga','total_biaya','pengantaran_perangkat','foto_masuk','foto_proses','foto_keluar','file_ba'];
    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_selesai' => 'date',
        'harga' => 'decimal:2',
        'teknisi' => 'array'
    ];
}

