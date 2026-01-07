<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom 'status' untuk menambahkan 'claimed'
        DB::statement('ALTER TABLE reports CHANGE status status ENUM("pending", "approved", "rejected", "returned", "claimed")');
    }

    public function down(): void
    {
        // Mengembalikan kolom 'status' ke kondisi semula
        DB::statement('ALTER TABLE reports CHANGE status status ENUM("pending", "approved", "rejected", "returned")');
    }
};
