<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AlatKantor extends Model {
    protected $table = 'alat_kantor';
    protected $fillable = ['nama_tool','qty','satuan','kondisi','tempat','keterangan'];
}
