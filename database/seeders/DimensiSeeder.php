<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Category;
use App\Models\User;

class DimensiSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Dim Status (Manual)
        $statuses = ['pending', 'approved', 'rejected', 'returned', 'claimed'];
        foreach ($statuses as $status) {
            DB::table('dim_status')->updateOrInsert(
                ['label_status' => $status],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Seed Dim Kategori (Dari tabel categories)
        $categories = Category::all();
        foreach ($categories as $category) {
            DB::table('dim_kategori')->updateOrInsert(
                ['original_category_id' => $category->id],
                [
                    'nama_kategori' => $category->name,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // 3. Seed Dim Lokasi (Dari tabel rooms & buildings)
        $rooms = Room::with('building')->get();
        foreach ($rooms as $room) {
            DB::table('dim_lokasi')->updateOrInsert(
                ['original_room_id' => $room->id],
                [
                    'nama_gedung' => $room->building->name ?? 'Unknown',
                    'nama_ruangan' => $room->name,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // 4. Seed Dim Validator (Dari tabel users role admin/petugas)
        $validators = User::whereIn('role', ['admin', 'petugas'])->get();
        foreach ($validators as $user) {
            DB::table('dim_validator')->updateOrInsert(
                ['original_user_id' => $user->id],
                [
                    'nama_lengkap' => $user->name,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}