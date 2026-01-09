<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use App\Models\User;
use App\Observers\ReportObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // =================================================================
        // BAGIAN 1: SCORECARDS
        // =================================================================
        
        $totalHilang = DB::table('fact_kehilangan')->count();
        $totalDitemukan = DB::table('fact_penemuan')->count();
        
        $stats = [
            'total_lost'       => $totalHilang,
            'total_found'      => $totalDitemukan,
            'pending_validations' => Report::where('status', 'pending')->count() + Claim::where('status', 'pending')->count(),
            'total_users'      => User::count(),
        ];

        // =================================================================
        // BAGIAN 2: GRAFIK ANALISIS (FIXED COLUMN NAMES)
        // =================================================================

        // A. GRAFIK "TOP 5 LOKASI ANGKER"
        $topLocations = DB::table('fact_kehilangan')
            ->leftJoin('dim_lokasi', 'fact_kehilangan.lokasi_id', '=', 'dim_lokasi.id')
            ->select(DB::raw('COALESCE(dim_lokasi.nama_gedung, "Lokasi Tidak Diketahui") as name'), DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // B. GRAFIK KATEGORI
        $reportsByCategory = DB::table('fact_kehilangan')
            ->leftJoin('dim_kategori', 'fact_kehilangan.kategori_id', '=', 'dim_kategori.id')
            ->select(DB::raw('COALESCE(dim_kategori.nama_kategori, "Kategori Tidak Diketahui") as name'), DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('name')
            ->get();

        // C. GRAFIK TREN BULANAN (REVISI DIM_WAKTU)
        $monthlyTrend = DB::table('fact_kehilangan')
            ->join('dim_waktu', 'fact_kehilangan.waktu_id', '=', 'dim_waktu.id')
            ->select('dim_waktu.bulan_nama as month', DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->where('dim_waktu.tahun', date('Y'))
            ->groupBy('dim_waktu.bulan_nama', 'dim_waktu.bulan_angka')
            ->orderBy('dim_waktu.bulan_angka')
            ->get();
        
        // E. GRAFIK HARI PALING "SIAL" (Day Analysis)
        $dayAnalysis = DB::table('fact_kehilangan')
            ->leftJoin('dim_waktu', 'fact_kehilangan.waktu_id', '=', 'dim_waktu.id')
            ->select(DB::raw('COALESCE(dim_waktu.hari_nama, "Hari Tidak Diketahui") as day'), DB::raw('SUM(jumlah_laporan_hilang) as total'))
            ->groupBy('day')
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Hari Tidak Diketahui')")
            ->get();

        // =================================================================
        // BAGIAN 3: DAFTAR LAPORAN (NEW & IMPROVED)
        // =================================================================
        
        $reportsQuery = Report::with(['user', 'category', 'room.building'])
            ->latest();

        // Filter berdasarkan Tipe (hilang/temuan)
        if ($request->filled('type')) {
            $reportsQuery->where('type', $request->type);
        }

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $reportsQuery->where('status', $request->status);
        }

        $reports = $reportsQuery->paginate(10)->withQueryString();

        return view('admin.dashboard', compact(
            'stats', 
            'topLocations', 
            'reportsByCategory', 
            'monthlyTrend',
            'dayAnalysis',
            'reports'
        ));
    }
    // ==========================================
    // API UNTUK GRAFIK TREN (FILTER WAKTU)
    // ==========================================
    public function getTrendData(Request $request)
    {
        $filter = $request->query('filter', 'monthly');
        $year = date('Y');

        // Definisi kolom grouping dan sorting
        if ($filter == 'daily') {
            $groupBy = 'dim_waktu.tanggal';
            $selectRaw = 'DATE_FORMAT(dim_waktu.tanggal, "%d %b") as label';
            $orderBy = 'dim_waktu.tanggal';
        } elseif ($filter == 'weekly') {
            $groupBy = 'dim_waktu.pekan_ke';
            $selectRaw = 'CONCAT("Pekan ", dim_waktu.pekan_ke) as label';
            $orderBy = 'dim_waktu.pekan_ke';
        } elseif ($filter == 'yearly') {
            $groupBy = 'dim_waktu.tahun';
            $selectRaw = 'dim_waktu.tahun as label';
            $orderBy = 'dim_waktu.tahun';
        } else { // monthly
            $groupBy = 'dim_waktu.bulan_angka';
            $selectRaw = 'dim_waktu.bulan_nama as label';
            $orderBy = 'dim_waktu.bulan_angka';
        }

        // Helper function untuk query agar lebih rapi dan konsisten
        $getData = function($table, $sumColumn) use ($year, $filter, $groupBy, $selectRaw, $orderBy) {
            $query = DB::table($table)
                ->join('dim_waktu', "$table.waktu_id", '=', 'dim_waktu.id')
                // Select sort_key juga untuk pengurutan nanti
                ->select(DB::raw($selectRaw), DB::raw("SUM($sumColumn) as total"), DB::raw("$orderBy as sort_key"))
                ->groupBy(DB::raw($groupBy));

            // Khusus monthly, group by nama bulan juga agar compliant dengan strict SQL mode
            if ($filter == 'monthly') {
                $query->groupBy('dim_waktu.bulan_nama');
            }

            if ($filter == 'yearly') {
                $query->where('dim_waktu.tahun', '>=', $year - 5);
            } else {
                $query->where('dim_waktu.tahun', $year);
            }

            return $query->orderBy(DB::raw($orderBy))->get();
        };

        $lostData = $getData('fact_kehilangan', 'jumlah_laporan_hilang');
        $foundData = $getData('fact_penemuan', 'jumlah_barang_masuk');

        // Gabungkan label dan urutkan berdasarkan sort_key (bukan label string)
        // Ini mencegah urutan bulan jadi alfabetis (April, Agustus, Desember...)
        $merged = $lostData->map(fn($item) => ['label' => $item->label, 'sort_key' => $item->sort_key])
            ->merge($foundData->map(fn($item) => ['label' => $item->label, 'sort_key' => $item->sort_key]))
            ->unique('label')
            ->sortBy('sort_key');

        $labels = $merged->pluck('label')->values();

        // Mapping data supaya urut sesuai label
        $lostMapped = $labels->map(fn($label) => $lostData->firstWhere('label', $label)->total ?? 0);
        $foundMapped = $labels->map(fn($label) => $foundData->firstWhere('label', $label)->total ?? 0);


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

    public function fixReportStatuses()
    {
        // Find all reports that are still 'approved'
        // but have a claim that is 'approved'.
        $reportsToFix = Report::where('status', 'approved')
            ->whereHas('claims', function ($query) {
                $query->where('status', 'approved');
            })
            ->get();

        $count = $reportsToFix->count();

        foreach ($reportsToFix as $report) {
            $report->update(['status' => 'returned']);
        }

        return "Fixed {$count} reports that had an approved claim but were not marked as 'returned'. You can now remove the temporary route used to run this fix.";
    }

    public function backfillAnalytics(ReportObserver $observer)
    {
        // Truncate tables for a clean sync
        DB::table('fact_kehilangan')->truncate();
        DB::table('fact_penemuan')->truncate();
        
        $reports = Report::all();
        $totalCount = $reports->count();
        $lostCount = 0;
        $foundCount = 0;

        foreach ($reports as $report) {
            if ($report->type === 'lost') {
                $lostCount++;
            } elseif ($report->type === 'found') {
                $foundCount++;
            }
            $observer->syncReportById($report->id);
        }

        return "Backfill complete. Total reports processed: {$totalCount}. Found reports synced: {$foundCount}. Lost reports synced: {$lostCount}.";
    }
}