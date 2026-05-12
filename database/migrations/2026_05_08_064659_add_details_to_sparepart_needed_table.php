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
            $table->text('kerusakan')->nullable()->after('tgl_selesai');
            $table->text('action')->nullable()->after('kerusakan');
            $table->text('pergantian_perangkat')->nullable()->after('status');
            $table->text('keterangan_tambahan')->nullable()->after('pergantian_perangkat');
            $table->decimal('total_biaya', 15, 2)->nullable()->after('harga');
            $table->string('pengantaran_perangkat')->nullable()->after('total_biaya');
        });
    }

    public function down(): void
    {
        Schema::table('sparepart_needed', function (Blueprint $table) {
            $table->dropColumn(['kerusakan', 'action', 'pergantian_perangkat', 'keterangan_tambahan', 'total_biaya', 'pengantaran_perangkat']);
        });
    }
};
