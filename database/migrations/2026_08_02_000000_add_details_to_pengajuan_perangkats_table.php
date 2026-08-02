<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_perangkats', function (Blueprint $table) {
            $table->text('details')->nullable()->after('alasan');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_perangkats', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
};
