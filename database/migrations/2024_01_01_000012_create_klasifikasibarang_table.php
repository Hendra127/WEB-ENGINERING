<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('klasifikasi_barang', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_keluar')->nullable();
            $table->string('nama_barang');
            $table->integer('qty')->default(1);
            $table->string('satuan')->default('Unit');
            $table->string('nama_penerima')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['MASUK','KELUAR'])->default('MASUK');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('klasifikasi_barang'); }
};
