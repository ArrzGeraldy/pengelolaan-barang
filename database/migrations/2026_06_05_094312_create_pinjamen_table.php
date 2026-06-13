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
        Schema::create('pinjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('restrict');
            $table->string('kode_peminjaman')->unique();
            $table->string('peminjam');
            $table->integer('jumlah_dipinjam');
            $table->dateTime('tanggal_pinjam');
            $table->dateTime('tanggal_dikembalikan');
            $table->enum('status', ['Dipinjam', 'Selesai', 'Terlambat'])->default('Dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamen');
    }
};
