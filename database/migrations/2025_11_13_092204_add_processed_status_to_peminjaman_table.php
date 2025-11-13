<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk SQLite, kita perlu recreate table
        $this->recreateTableWithNewStatus();
    }

    private function recreateTableWithNewStatus(): void
    {
        // Backup data
        $backupData = DB::table('peminjaman')->get();

        // Drop table
        Schema::dropIfExists('peminjaman');

        // Create new table dengan status processed
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('laporans')->onDelete('cascade');
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->text('keperluan');
            $table->enum('status', ['pending', 'validated', 'approved', 'processed', 'completed', 'rejected', 'returned'])->default('pending');
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

        // Restore data dengan mapping status
        foreach ($backupData as $data) {
            $status = $data->status;
            // Jika status adalah 'completed' yang lama, ubah menjadi 'processed'
            if ($status === 'completed') {
                $status = 'processed';
            }

            DB::table('peminjaman')->insert([
                'id' => $data->id,
                'kode_peminjaman' => $data->kode_peminjaman,
                'user_id' => $data->user_id,
                'barang_id' => $data->barang_id,
                'jumlah_pinjam' => $data->jumlah_pinjam,
                'tanggal_pinjam' => $data->tanggal_pinjam,
                'tanggal_kembali' => $data->tanggal_kembali,
                'keperluan' => $data->keperluan,
                'status' => $status,
                'catatan_admin' => $data->catatan_admin,
                'validated_by' => $data->validated_by,
                'validated_at' => $data->validated_at,
                'approved_by' => $data->approved_by,
                'approved_at' => $data->approved_at,
                'completed_by' => $data->completed_by,
                'completed_at' => $data->completed_at,
                'returned_at' => $data->returned_at,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        // Backup data
        $backupData = DB::table('peminjaman')->get();

        // Drop table
        Schema::dropIfExists('peminjaman');

        // Create original table structure tanpa status processed
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

        // Restore data dengan mapping status
        foreach ($backupData as $data) {
            $status = $data->status;
            // Jika status adalah 'processed', ubah menjadi 'completed'
            if ($status === 'processed') {
                $status = 'completed';
            }

            DB::table('peminjaman')->insert([
                'id' => $data->id,
                'kode_peminjaman' => $data->kode_peminjaman,
                'user_id' => $data->user_id,
                'barang_id' => $data->barang_id,
                'jumlah_pinjam' => $data->jumlah_pinjam,
                'tanggal_pinjam' => $data->tanggal_pinjam,
                'tanggal_kembali' => $data->tanggal_kembali,
                'keperluan' => $data->keperluan,
                'status' => $status,
                'catatan_admin' => $data->catatan_admin,
                'validated_by' => $data->validated_by,
                'validated_at' => $data->validated_at,
                'approved_by' => $data->approved_by,
                'approved_at' => $data->approved_at,
                'completed_by' => $data->completed_by,
                'completed_at' => $data->completed_at,
                'returned_at' => $data->returned_at,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at,
            ]);
        }
    }
};
