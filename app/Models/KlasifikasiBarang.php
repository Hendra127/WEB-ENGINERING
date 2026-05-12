<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiBarang extends Model {
    protected $table = 'klasifikasi_barang';
    protected $fillable = ['tgl_masuk','tgl_keluar','nama_barang','qty','satuan','nama_penerima','lokasi','status','keterangan'];
    protected $casts = ['tgl_masuk'=>'date','tgl_keluar'=>'date'];
}
