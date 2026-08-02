<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPerangkat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_perangkat',
        'jumlah',
        'alasan',
        'details',
        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
