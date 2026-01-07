<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Dimension Tables
        Schema::create('dim_waktu', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->integer('hari_ke'); // 1-31
            $table->string('hari_nama'); // Senin, Selasa...
            $table->integer('bulan_angka'); // 1-12
            $table->string('bulan_nama'); // Januari...
            $table->year('tahun');
            $table->integer('pekan_ke'); // Week number
            $table->timestamps();
        });

        Schema::create('dim_lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung');
            $table->string('nama_ruangan');
            $table->timestamps();
        });

        Schema::create('dim_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::create('dim_status', function (Blueprint $table) {
            $table->id();
            $table->string('label_status'); // Pending, Approved, Returned
            $table->timestamps();
        });

        Schema::create('dim_validator', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->timestamps();
        });

        // 2. Fact Tables
        Schema::create('fact_kehilangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waktu_id')->constrained('dim_waktu');
            $table->foreignId('lokasi_id')->constrained('dim_lokasi');
            $table->foreignId('kategori_id')->constrained('dim_kategori');
            $table->integer('jumlah_laporan_hilang')->default(0);
            $table->timestamps();
        });

        Schema::create('fact_penemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waktu_id')->constrained('dim_waktu');
            $table->foreignId('kategori_id')->constrained('dim_kategori');
            $table->foreignId('status_id')->constrained('dim_status');
            $table->integer('jumlah_barang_masuk')->default(0);
            $table->timestamps();
        });

        Schema::create('fact_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validator_id')->constrained('dim_validator');
            $table->integer('jumlah_kembali')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fact_pengembalian');
        Schema::dropIfExists('fact_penemuan');
        Schema::dropIfExists('fact_kehilangan');
        Schema::dropIfExists('dim_validator');
        Schema::dropIfExists('dim_status');
        Schema::dropIfExists('dim_kategori');
        Schema::dropIfExists('dim_lokasi');
        Schema::dropIfExists('dim_waktu');
    }
};