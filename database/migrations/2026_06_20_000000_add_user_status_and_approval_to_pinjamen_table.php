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
        // Kolom sudah ditambahkan di migration create_pinjamen_table
        // Migration ini tidak perlu dijalankan
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada kolom yang dihapus di sini
    }
};
