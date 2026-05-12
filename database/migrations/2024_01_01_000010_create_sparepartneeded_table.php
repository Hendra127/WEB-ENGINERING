<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sparepart_needed', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi_pekerjaan');
            $table->string('ruang')->nullable();
            $table->string('jenis_pekerjaan');
            $table->string('type')->nullable();
            $table->integer('qty')->default(1);
            $table->string('satuan')->default('Unit');
            $table->string('teknisi')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['PENDING','PROSES','DONE'])->default('PENDING');
            $table->decimal('harga', 15, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sparepart_needed'); }
};
