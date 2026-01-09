<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportObserver
{
    public function created(Report $report)
    {
        $this->syncToFact($report);
    }

    public function updated(Report $report)
    {
        // Hapus data lama, lalu insert ulang (cara paling aman untuk update)
        $this->deleted($report);
        $this->syncToFact($report);
    }

    public function deleted(Report $report)
    {
        DB::table('fact_kehilangan')->where('report_id', $report->id)->delete();
        DB::table('fact_penemuan')->where('report_id', $report->id)->delete();
    }
    
    /**
     * Public method to allow manual syncing, useful for backfilling.
     */
    public function syncReportById($reportId)
    {
        $report = Report::find($reportId);
        if ($report) {
            $this->syncToFact($report);
        }
    }

    private function syncToFact(Report $report)
    {
        // 1. Pastikan Dimensi Ada & Ambil ID-nya
        $waktuId = $this->ensureWaktu($report->event_date ?? $report->created_at);
        $lokasiId = $this->ensureLokasi($report->room_id);
        $kategoriId = $this->ensureKategori($report->category_id);
        $statusId = $this->ensureStatus($report->status);

        // --- SKENARIO 1: BARANG HILANG (Insert ke fact_kehilangan) ---
        if ($report->type === 'lost') {
            DB::table('fact_kehilangan')->updateOrInsert(
                ['report_id' => $report->id],
                [
                    'waktu_id'              => $waktuId,
                    'lokasi_id'             => $lokasiId,
                    'kategori_id'           => $kategoriId,
                    'jumlah_laporan_hilang' => 1,
                    'created_at'            => $report->created_at,
                    'updated_at'            => $report->updated_at,
                ]
            );
        }
        // --- SKENARIO 2: BARANG DITEMUKAN (Insert ke fact_penemuan) ---
        elseif ($report->type === 'found') {
            DB::table('fact_penemuan')->updateOrInsert(
                ['report_id' => $report->id],
                [
                    'waktu_id'            => $waktuId,
                    'lokasi_id'           => $lokasiId,
                    'kategori_id'         => $kategoriId,
                    'status_id'           => $statusId,
                    'jumlah_barang_masuk' => 1,
                    'created_at'          => $report->created_at,
                    'updated_at'          => $report->updated_at,
                ]
            );
        }
    }

    // --- Helper Methods untuk Dimensi (Auto-Create jika belum ada) ---

    private function ensureWaktu($dateStr)
    {
        $date = Carbon::parse($dateStr)->startOfDay();
        
        $waktu = DB::table('dim_waktu')->where('tanggal', $date->toDateString())->first();

        if ($waktu) {
            return $waktu->id;
        }
        
        // Jika tidak ada, buat record baru dan kembalikan ID-nya
        return DB::table('dim_waktu')->insertGetId([
            'tanggal' => $date->toDateString(),
            'hari_ke' => $date->day,
            'hari_nama' => $date->translatedFormat('l'),
            'bulan_angka' => $date->month,
            'bulan_nama' => $date->translatedFormat('F'),
            'tahun' => $date->year,
            'pekan_ke' => $date->weekOfYear,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function ensureLokasi($roomId)
    {
        if (!$roomId) return null;

        $lokasi = DB::table('dim_lokasi')->where('original_room_id', $roomId)->first();
        if ($lokasi) return $lokasi->id;

        $room = Room::with('building')->find($roomId);
        if (!$room) return null;

        return DB::table('dim_lokasi')->insertGetId([
            'original_room_id' => $room->id,
            'nama_gedung' => optional($room->building)->name ?? 'Unknown',
            'nama_ruangan' => $room->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function ensureKategori($catId)
    {
        if (!$catId) return null;
        
        $kategori = DB::table('dim_kategori')->where('original_category_id', $catId)->first();
        if ($kategori) return $kategori->id;

        $cat = Category::find($catId);
        if (!$cat) return null;

        return DB::table('dim_kategori')->insertGetId([
            'original_category_id' => $cat->id,
            'nama_kategori' => $cat->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function ensureStatus($statusLabel)
    {
        if (!$statusLabel) return null;

        $status = DB::table('dim_status')->where('label_status', $statusLabel)->first();
        if ($status) return $status->id;

        return DB::table('dim_status')->insertGetId([
            'label_status' => $statusLabel,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}