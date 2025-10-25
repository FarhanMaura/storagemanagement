<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_laporan', ['masuk', 'keluar']);
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->text('keterangan')->nullable();
            $table->string('lokasi');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Index untuk pencarian
            $table->index('kode_barang');
            $table->index('nama_barang');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
