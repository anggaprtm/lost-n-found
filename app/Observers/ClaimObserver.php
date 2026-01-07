<?php

namespace App\Observers;

use App\Models\Claim;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClaimObserver
{
    public function updated(Claim $claim)
    {
        // Cek: Apakah status BERUBAH jadi 'approved'?
        if ($claim->isDirty('status') && $claim->status == 'approved') {
            
            $report = $claim->report; // Ambil data laporannya
            
            // 1. Hitung Durasi (KPI Petugas)
            // Selisih hari dari laporan dibuat sampai klaim disetujui
            $durasi_hari = $claim->updated_at->diffInDays($report->created_at);
            // Kalau 0 hari (kurang dari 24 jam), anggap 1 hari
            if ($durasi_hari == 0) $durasi_hari = 1;

            // 2. Siapkan Data Pendukung
            $waktu_id = (int) now()->format('Ymd'); // Waktu pengembalian (hari ini)
            
            // Cari lokasi & kategori asli barangnya
            $lokasi_id = DB::table('dim_lokasi')->where('original_room_id', $report->room_id)->value('id') ?? 1;
            $kategori_id = DB::table('dim_kategori')->where('original_category_id', $report->category_id)->value('id') ?? 1;
            
            // Ambil ID Petugas yang memvalidasi (dari kolom validator_id di klaim)
            // Kalau kosong, pakai ID 1 (System)
            $petugas_id = $claim->validator_id ?? 1;

            // 3. Simpan ke Fact Pengembalian (FIXED SCHEMA)
            try {
                DB::table('fact_pengembalian')->insert([
                    'waktu_id'            => $waktu_id,
                    'lokasi_id'           => $lokasi_id,
                    'kategori_id'         => $kategori_id,
                    // FIX: Ganti 'karyawan_id' jadi 'validator_id'
                    'validator_id'        => $petugas_id, 
                    // FIX: Ganti 'durasi_pengembalian' jadi 'durasi_penyelesaian'
                    'durasi_penyelesaian' => $durasi_hari, 
                    'jumlah_kembali'      => 1
                ]);
            } catch (\Exception $e) {
                // Silent error
            }
        }
    }
}