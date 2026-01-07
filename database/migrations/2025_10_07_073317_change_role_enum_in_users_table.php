<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengubah definisi kolom 'role' untuk mengizinkan 'pengguna'
            $table->enum('role', ['admin', 'petugas', 'pengguna'])->default('pengguna')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan ke kondisi semula jika migrasi di-rollback
            $table->enum('role', ['admin', 'petugas', 'mahasiswa'])->default('mahasiswa')->change();
        });
    }
};