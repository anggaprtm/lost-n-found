<?php

namespace App\Observers;

use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportObserver
{
    public function created(Report $report)
    {
        // 1. Siapkan WAKTU_ID (Format: YYYYMMDD)
        // Kita ambil tanggal kejadian biar akurat
        $tanggal = $report->event_date ?? $report->created_at; 
        $waktu_id = (int) Carbon::parse($tanggal)->format('Ymd');

        // 2. Siapkan LOKASI_ID (Buat Grafik "Lokasi Angker")
        // Cari ID di dim_lokasi yang cocok sama room_id laporan
        $lokasi_id = DB::table('dim_lokasi')
            ->where('original_room_id', $report->room_id)
            ->value('id');

        // Kalau lokasi baru/gak ketemu, pake ID 1 (Lain-lain) biar gak error
        if (!$lokasi_id) $lokasi_id = 1;

        // 3. Siapkan KATEGORI_ID
        $kategori_id = DB::table('dim_kategori')
            ->where('original_category_id', $report->category_id)
            ->value('id');
        if (!$kategori_id) $kategori_id = 1;

        // --- SKENARIO 1: BARANG HILANG (Insert ke fact_kehilangan) ---
        if ($report->type == 'lost' || $report->type == 'Kehilangan') {
            DB::table('fact_kehilangan')->insert([
                'waktu_id'              => $waktu_id,
                'lokasi_id'             => $lokasi_id,
                'kategori_id'           => $kategori_id,
                'jumlah_laporan_hilang' => 1,
                // Kolom lain kalau ada di tabel fact kamu, tambahin di sini
            ]);
        }

        // --- SKENARIO 2: BARANG DITEMUKAN (Insert ke fact_penemuan) ---
        elseif ($report->type == 'found' || $report->type == 'Penemuan') {
            DB::table('fact_penemuan')->insert([
                'waktu_id'            => $waktu_id,
                'lokasi_id'           => $lokasi_id,
                'kategori_id'         => $kategori_id,
                'status_id'           => 1, // Default ID 1 = Pending
                'jumlah_barang_masuk' => 1,
            ]);
        }
    }
}