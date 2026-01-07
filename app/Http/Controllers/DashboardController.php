<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // PENTING: Untuk akses Data Warehouse

class DashboardController extends Controller
{
    // Router untuk mengarahkan user berdasarkan role
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'petugas':
                return redirect()->route('petugas.dashboard');
            case 'mahasiswa':
            default:
                return $this->userDashboard(); // Langsung panggil method di bawah
        }
    }

    public function userDashboard()
    {
        $userId = Auth::id();

        // =================================================================
        // 1. STATUS TRACKER (Nasib Laporan Saya)
        // =================================================================
        
        // Pending: Laporan saya ATAU Klaim saya yang belum divalidasi
        $pendingCount = Report::where('user_id', $userId)->where('status', 'pending')->count() 
                      + Claim::where('user_id', $userId)->where('status', 'pending')->count();

        // Action Needed: Laporan kehilangan saya yang statusnya sudah 'found' (Ditemukan orang lain/petugas)
        // Artinya user harus segera ke pusat layanan.
        $readyToPickup = Report::where('user_id', $userId)
                        ->where('type', 'lost')
                        ->where('status', 'found') // Atau 'approved' tergantung flow kamu
                        ->count();

        // Completed: Laporan selesai atau Klaim disetujui
        $completedCount = Report::where('user_id', $userId)->where('status', 'returned')->count()
                        + Claim::where('user_id', $userId)->where('status', 'approved')->count();

        $myStats = [
            'pending' => $pendingCount,
            'ready'   => $readyToPickup,
            'history' => $completedCount
        ];

        // =================================================================
        // 2. GAMIFICATION: GOOD SAMARITAN SCORE
        // =================================================================
        // Seberapa sering user ini berkontribusi menemukan barang?
        $goodDeeds = Report::where('user_id', $userId)
                    ->where('type', 'found')
                    ->count();

        // Tentukan Level Kebaikan
        $samaritanLevel = 'Warga Biasa';
        $nextLevel = 5;
        $progress = ($goodDeeds / 5) * 100; // Persentase ke level berikutnya

        if ($goodDeeds >= 5) {
            $samaritanLevel = 'Pahlawan Kampus';
            $nextLevel = 10;
            $progress = 100;
        } elseif ($goodDeeds >= 1) {
            $samaritanLevel = 'Orang Baik';
            $progress = ($goodDeeds / 5) * 100;
        }

        $gamification = [
            'score' => $goodDeeds,
            'level' => $samaritanLevel,
            'progress' => $progress
        ];

        // =================================================================
        // 3. PUBLIC INSIGHT: ZONA RAWAN (DATA WAREHOUSE)
        // =================================================================
        // Memberi info ke user: "Hati-hati di gedung ini!"
        // Mengambil data dari Fact Table Kehilangan
        $rawanZones = DB::table('fact_kehilangan')
            ->join('dim_lokasi', 'fact_kehilangan.lokasi_id', '=', 'dim_lokasi.id')
            ->select('dim_lokasi.nama_gedung as name', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('dim_lokasi.nama_gedung')
            ->orderByDesc('total')
            ->limit(3) // Top 3 Gedung Rawan
            ->get();

        // =================================================================
        // 4. RIWAYAT LAPORAN (TRANSAKSI REALTIME)
        // =================================================================
        
        $recentReports = Report::with(['category', 'room.building'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['report'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'myStats', 
            'gamification', 
            'rawanZones', 
            'recentReports', 
            'recentClaims'
        ));
    }
}