<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->bigIncrements('penjualan_id'); // Primary Key
            $table->unsignedBigInteger('user_id')->index(); // Foreign Key ke tabel m_user
            $table->string('pembeli', 50);
            $table->string('penjualan_kode', 20)->unique(); // Kode unik agar tidak ada duplikasi
            $table->datetime('penjualan_tanggal');
            $table->timestamps(); // created_at & updated_at

            // Definisi Foreign Key
            $table->foreign('user_id')->references('user_id')->on('m_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan');
    }
};
