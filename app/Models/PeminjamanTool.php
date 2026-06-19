<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanTool extends Model
{
    protected $table = 'peminjaman_tools';

    protected $fillable = [
        'alat_kantor_id',
        'nama_alat',
        'nama_peminjam',
        'qty',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
    ];

    public function alatKantor()
    {
        return $this->belongsTo(AlatKantor::class, 'alat_kantor_id');
    }
}
