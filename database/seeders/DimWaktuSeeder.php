<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DimWaktuSeeder extends Seeder
{
    public function run()
    {
        // Kita generate data dari tahun 2024 sampai 2026 (sesuaikan kebutuhan)
        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2026, 12, 31);

        while ($start->lte($end)) {
            $id = $start->format('Ymd'); // Contoh: 20260107
            
            // Cek duplicate agar aman jika dijalankan berulang
            if (DB::table('dim_waktu')->where('id', $id)->doesntExist()) {
                DB::table('dim_waktu')->insert([
                    'id' => $id, // Memaksa ID sesuai format tanggal
                    'tanggal' => $start->format('Y-m-d'),
                    'hari_ke' => $start->day,
                    'hari_nama' => $start->translatedFormat('l'), // Senin, Selasa...
                    'bulan_angka' => $start->month,
                    'bulan_nama' => $start->translatedFormat('F'), // Januari...
                    'tahun' => $start->year,
                    'pekan_ke' => $start->weekOfYear,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $start->addDay();
        }
    }
}