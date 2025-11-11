<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL, kita perlu alter column enum
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'validated', 'approved', 'completed', 'rejected', 'returned') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'returned') DEFAULT 'pending'");
    }
};
