<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // =================================================================
        // BAGIAN 1: SCORECARDS
        // =================================================================
        
        $totalHilang = DB::table('fact_kehilangan')->sum('jumlah_laporan_hilang') ?? 0;
        
        try {
            $totalKembali = DB::table('fact_pengembalian')->sum('jumlah_kembali') ?? 0;
        } catch (\Exception $e) {
            $totalKembali = 0; 
        }

        $successRate = ($totalHilang > 0) ? round(($totalKembali / $totalHilang) * 100, 1) : 0;

        $stats = [
            'total_reports'    => $totalHilang,
            'total_returned'   => $totalKembali,
            'success_rate'     => $successRate,
            'pending_validations' => Report::where('status', 'pending')->count() + Claim::where('status', 'pending')->count(),
            'total_users'      => User::count(),
        ];

        // =================================================================
        // BAGIAN 2: GRAFIK ANALISIS (FIXED COLUMN NAMES)
        // =================================================================

        // A. GRAFIK "TOP 5 LOKASI ANGKER"
        // Menggunakan kolom 'nama_gedung' sesuai schema kamu
        $topLocations = DB::table('fact_kehilangan')
            ->join('dim_lokasi', 'fact_kehilangan.lokasi_id', '=', 'dim_lokasi.id')
            ->select('dim_lokasi.nama_gedung as name', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('dim_lokasi.nama_gedung')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // B. GRAFIK KATEGORI
        // Menggunakan kolom 'nama_kategori' sesuai schema kamu
        $reportsByCategory = DB::table('fact_kehilangan')
            ->join('dim_kategori', 'fact_kehilangan.kategori_id', '=', 'dim_kategori.id')
            ->select('dim_kategori.nama_kategori as name', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('dim_kategori.nama_kategori')
            ->get();

        // C. GRAFIK TREN BULANAN (REVISI DIM_WAKTU)
        // Masalah tadi di sini: Ganti 'bulan' jadi 'bulan_nama'
        $monthlyTrend = DB::table('fact_kehilangan')
            ->join('dim_waktu', 'fact_kehilangan.waktu_id', '=', 'dim_waktu.id')
            ->select('dim_waktu.bulan_nama as month', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->where('dim_waktu.tahun', date('Y'))
            // Kita group by bulan_nama DAN bulan_angka biar bisa diurutkan
            ->groupBy('dim_waktu.bulan_nama', 'dim_waktu.bulan_angka')
            ->orderBy('dim_waktu.bulan_angka') // Urutkan berdasarkan angka (1, 2, 3...)
            ->get();
        
        // D. GRAFIK KINERJA VALIDATOR (Leaderboard Petugas)
        // Siapa petugas yang paling rajin memproses pengembalian?
        $validatorPerformance = DB::table('fact_pengembalian')
            // Asumsi validator_id di fact connect ke id di dim_validator
            ->join('dim_validator', 'fact_pengembalian.validator_id', '=', 'dim_validator.id')
            ->select('dim_validator.nama_lengkap as name', DB::raw('SUM(jumlah_kembali) as total'))
            ->groupBy('dim_validator.nama_lengkap')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // E. GRAFIK HARI PALING "SIAL" (Day Analysis)
        // Hari apa barang paling sering hilang?
        $dayAnalysis = DB::table('fact_kehilangan')
            ->join('dim_waktu', 'fact_kehilangan.waktu_id', '=', 'dim_waktu.id')
            // Pastikan kolom 'hari_nama' ada di dim_waktu kamu
            ->select('dim_waktu.hari_nama as day', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('dim_waktu.hari_nama')
            // Order by field biar urut Senin-Minggu (Opsional, kalau database support FIELD)
            ->orderByRaw("FIELD(dim_waktu.hari_nama, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get();

        // =================================================================
        // BAGIAN 3: OPERASIONAL
        // =================================================================
        
        $recentReports = Report::with(['user', 'category', 'room.building'])
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['user', 'report'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'topLocations', 
            'reportsByCategory', 
            'monthlyTrend',
            'validatorPerformance', // <--- Baru
            'dayAnalysis',          // <--- Baru
            'recentReports', 
            'recentClaims'
        ));
    }
    // ==========================================
    // API UNTUK GRAFIK TREN (FILTER WAKTU)
    // ==========================================
    public function getTrendData(Request $request)
    {
        $filter = $request->query('filter', 'monthly'); // default monthly
        $year = date('Y');

        // Tentukan kolom grouping & select berdasarkan filter
        if ($filter == 'daily') {
            $groupBy = 'dim_waktu.tanggal';
            $selectRaw = 'DATE_FORMAT(dim_waktu.tanggal, "%d %b") as label';
        } elseif ($filter == 'weekly') {
            $groupBy = 'dim_waktu.pekan_ke';
            $selectRaw = 'CONCAT("Pekan ", dim_waktu.pekan_ke) as label';
        } elseif ($filter == 'yearly') {
            $groupBy = 'dim_waktu.tahun';
            $selectRaw = 'dim_waktu.tahun as label';
        } else { // monthly
            $groupBy = 'dim_waktu.bulan_angka'; // Group by angka biar urut
            $selectRaw = 'dim_waktu.bulan_nama as label';
        }

        // 1. DATA KEHILANGAN (Garis Merah)
        $lostQuery = DB::table('fact_kehilangan')
            ->join('dim_waktu', 'fact_kehilangan.waktu_id', '=', 'dim_waktu.id')
            ->select(DB::raw($selectRaw), DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy(DB::raw($groupBy)); // Group by kolom dinamis
            
        // Filter tahun ini (kecuali filter yearly, tampilkan 5 tahun terakhir)
        if ($filter == 'yearly') {
             $lostQuery->where('dim_waktu.tahun', '>=', $year - 5);
        } else {
             $lostQuery->where('dim_waktu.tahun', $year);
        }
        
        // Handle sorting
        if ($filter == 'monthly') {
             // Trik khusus monthly: sertakan bulan_nama di group by
             $lostQuery->groupBy('dim_waktu.bulan_nama')->orderBy('dim_waktu.bulan_angka');
        } else {
             $lostQuery->orderBy(DB::raw($groupBy));
        }

        $lostData = $lostQuery->get();

        // 2. DATA PENEMUAN (Garis Hijau)
        // (Logic sama persis, cuma beda tabel sumber)
        $foundQuery = DB::table('fact_penemuan')
            ->join('dim_waktu', 'fact_penemuan.waktu_id', '=', 'dim_waktu.id')
            ->select(DB::raw($selectRaw), DB::raw('SUM(jumlah_barang_masuk) as total'))
            ->groupBy(DB::raw($groupBy));

        if ($filter == 'yearly') {
             $foundQuery->where('dim_waktu.tahun', '>=', $year - 5);
        } else {
             $foundQuery->where('dim_waktu.tahun', $year);
        }

        if ($filter == 'monthly') {
             $foundQuery->groupBy('dim_waktu.bulan_nama')->orderBy('dim_waktu.bulan_angka');
        } else {
             $foundQuery->orderBy(DB::raw($groupBy));
        }

        $foundData = $foundQuery->get();

        // 3. Gabungkan Data (Merge Labels)
        // Kita harus memastikan label sumbu X konsisten
        $labels = $lostData->pluck('label')->merge($foundData->pluck('label'))->unique()->values();

        // Mapping data supaya urut sesuai label
        $lostMapped = $labels->map(function($label) use ($lostData) {
            return $lostData->firstWhere('label', $label)->total ?? 0;
        });

        $foundMapped = $labels->map(function($label) use ($foundData) {
            return $foundData->firstWhere('label', $label)->total ?? 0;
        });

        return response()->json([
            'labels' => $labels,
            'lost' => $lostMapped,
            'found' => $foundMapped
        ]);
    }

    // ==========================================
    // API UNTUK DRILL-DOWN LOKASI (DETAIL RUANGAN)
    // ==========================================
    public function getLocationDetails(Request $request)
    {
        $gedung = $request->query('gedung');

        if (!$gedung) return response()->json([], 400);

        // Ambil detail ruangan berdasarkan nama gedung yang diklik
        $rooms = DB::table('fact_kehilangan')
            ->join('dim_lokasi', 'fact_kehilangan.lokasi_id', '=', 'dim_lokasi.id')
            ->select('dim_lokasi.nama_ruangan', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->where('dim_lokasi.nama_gedung', $gedung)
            ->groupBy('dim_lokasi.nama_ruangan')
            ->orderByDesc('total')
            ->get();

        return response()->json($rooms);
    }
}