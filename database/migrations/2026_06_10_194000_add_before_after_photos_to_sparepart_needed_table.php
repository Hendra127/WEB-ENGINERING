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
            $table->string('foto_sebelum')->nullable()->after('foto_masuk');
            $table->string('foto_sesudah')->nullable()->after('foto_proses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sparepart_needed', function (Blueprint $table) {
            $table->dropColumn(['foto_sebelum', 'foto_sesudah']);
        });
    }
};
