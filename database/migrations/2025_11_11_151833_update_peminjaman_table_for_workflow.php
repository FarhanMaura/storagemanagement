<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya tambah kolom jika belum ada
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

        // Update enum status dengan approach yang lebih aman
        $pdo = DB::getPdo();
        $check = DB::select("PRAGMA table_info(peminjaman)");
        $statusColumn = collect($check)->firstWhere('name', 'status');

        if ($statusColumn && str_contains($statusColumn->type, 'enum')) {
            // Untuk SQLite, kita perlu recreate table untuk ganti enum
            $this->recreateTableWithNewEnum();
        } else {
            // Jika bukan enum, langsung update values yang tidak valid
            DB::table('peminjaman')->where('status', 'validated')->update(['status' => 'pending']);
            DB::table('peminjaman')->where('status', 'completed')->update(['status' => 'approved']);
        }
    }

    private function recreateTableWithNewEnum(): void
    {
        // 1. Create temporary table
        DB::statement('CREATE TABLE peminjaman_temp AS SELECT * FROM peminjaman');

        // 2. Update invalid status values
        DB::table('peminjaman_temp')->where('status', 'validated')->update(['status' => 'pending']);
        DB::table('peminjaman_temp')->where('status', 'completed')->update(['status' => 'approved']);

        // 3. Drop original table
        Schema::drop('peminjaman');

        // 4. Create new table with correct enum
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('laporans')->onDelete('cascade');
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('keperluan');
            $table->enum('status', ['pending', 'validated', 'approved', 'completed', 'rejected', 'returned'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });

        // 5. Copy data back
        $columns = [
            'id', 'kode_peminjaman', 'user_id', 'barang_id', 'jumlah_pinjam',
            'tanggal_pinjam', 'tanggal_kembali', 'keperluan', 'status',
            'catatan_admin', 'validated_by', 'validated_at', 'approved_by',
            'approved_at', 'completed_by', 'completed_at', 'returned_at',
            'created_at', 'updated_at'
        ];

        $existingColumns = Schema::getColumnListing('peminjaman_temp');
        $columnsToCopy = array_intersect($columns, $existingColumns);
        $selectColumns = implode(', ', $columnsToCopy);

        DB::statement("INSERT INTO peminjaman ({$selectColumns}) SELECT {$selectColumns} FROM peminjaman_temp");

        // 6. Drop temporary table
        Schema::drop('peminjaman_temp');
    }

    public function down(): void
    {
        // Untuk rollback, kita juga perlu recreate table dengan enum lama
        $this->recreateTableWithOldEnum();
    }

    private function recreateTableWithOldEnum(): void
    {
        // 1. Create temporary table
        DB::statement('CREATE TABLE peminjaman_temp AS SELECT * FROM peminjaman');

        // 2. Update invalid status values
        DB::table('peminjaman_temp')->where('status', 'validated')->update(['status' => 'pending']);
        DB::table('peminjaman_temp')->where('status', 'completed')->update(['status' => 'approved']);

        // 3. Drop current table
        Schema::drop('peminjaman');

        // 4. Create original table structure
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('laporans')->onDelete('cascade');
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('keperluan');
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
        });

        // 5. Copy compatible data back
        $columns = [
            'id', 'kode_peminjaman', 'user_id', 'barang_id', 'jumlah_pinjam',
            'tanggal_pinjam', 'tanggal_kembali', 'keperluan', 'status',
            'catatan_admin', 'approved_at', 'returned_at', 'created_at', 'updated_at'
        ];

        $existingColumns = Schema::getColumnListing('peminjaman_temp');
        $columnsToCopy = array_intersect($columns, $existingColumns);
        $selectColumns = implode(', ', $columnsToCopy);

        DB::statement("INSERT INTO peminjaman ({$selectColumns}) SELECT {$selectColumns} FROM peminjaman_temp");

        // 6. Drop temporary table
        Schema::drop('peminjaman_temp');
    }
};
