<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom baru jika belum ada
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'validated_by')) {
                $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('peminjaman', 'validated_at')) {
                $table->timestamp('validated_at')->nullable();
            }
            if (!Schema::hasColumn('peminjaman', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('peminjaman', 'completed_by')) {
                $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('peminjaman', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });

        // 2. Update struktur ENUM status langsung menggunakan MySQL Query
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'validated', 'approved', 'completed', 'rejected', 'returned') DEFAULT 'pending'");

        // 3. Update data status sesuai logika sebelumnya
        DB::table('peminjaman')->where('status', 'validated')->update(['status' => 'pending']);
        DB::table('peminjaman')->where('status', 'completed')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        // 1. Rollback struktur ENUM ke versi awal
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'returned') DEFAULT 'pending'");

        // 2. Rollback data
        DB::table('peminjaman')->where('status', 'validated')->update(['status' => 'pending']);
        DB::table('peminjaman')->where('status', 'completed')->update(['status' => 'approved']);

        // 3. Hapus kolom yang ditambahkan saat up()
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['validated_by', 'validated_at']);

            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by']);

            $table->dropForeign(['completed_by']);
            $table->dropColumn(['completed_by', 'completed_at']);
        });
    }
};