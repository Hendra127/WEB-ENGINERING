<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alat_kantor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tool');
            $table->integer('qty')->default(1);
            $table->string('satuan')->default('Unit');
            $table->enum('kondisi', ['BAIK','RUSAK RINGAN','RUSAK BERAT'])->default('BAIK');
            $table->string('tempat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alat_kantor'); }
};
