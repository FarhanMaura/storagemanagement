<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_rusak_kembali')->default(0)->nullable()->after('jumlah_pinjam');
            $table->text('catatan_kembali')->nullable()->after('catatan_admin');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['jumlah_rusak_kembali', 'catatan_kembali']);
        });
    }
};
