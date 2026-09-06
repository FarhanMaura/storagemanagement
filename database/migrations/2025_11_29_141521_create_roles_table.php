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
        // Roles table removed - using simple role string in users table
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Roles table removed
    }
};
