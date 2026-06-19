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
        Schema::table('sparepart_needed', function (Blueprint $table) {
            $table->string('foto_masuk')->nullable()->after('keterangan');
            $table->string('foto_proses')->nullable()->after('foto_masuk');
            $table->string('foto_keluar')->nullable()->after('foto_proses');
            $table->string('file_ba')->nullable()->after('foto_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_needed', function (Blueprint $table) {
            $table->dropColumn(['foto_masuk', 'foto_proses', 'foto_keluar', 'file_ba']);
        });
    }
};
