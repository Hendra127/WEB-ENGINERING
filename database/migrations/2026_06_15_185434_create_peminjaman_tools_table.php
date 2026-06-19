<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_kantor_id')->nullable()->constrained('alat_kantor')->onDelete('set null');
            $table->string('nama_alat');
            $table->string('nama_peminjam');
            $table->integer('qty')->default(1);
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->enum('status', ['DIPINJAM', 'DIKEMBALI'])->default('DIPINJAM');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_tools');
    }
};
