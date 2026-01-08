<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Claim;
use App\Models\Category;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PetugasController extends Controller
{
    use AuthorizesRequests;

    public function dashboard()
    {
        $userId = Auth::id();
        $currentMonth = date('m'); // Hati-hati: ini string "01"-"12"
        $currentYear = date('Y');

        // =================================================================
        // 1. KPI CARDS
        // =================================================================

        // Card 1: Tugas Menumpuk (Laporan "Found" yg perlu divalidasi/disimpan)
        $pendingTasks = Report::where('status', 'pending')->count();

        // Card 2: Barang Temuan Baru (Bulan Ini)
        // Kita hitung dari fact_penemuan
        $itemsInMonth = DB::table('fact_penemuan')
            ->join('dim_waktu', 'fact_penemuan.waktu_id', '=', 'dim_waktu.id')
            ->where('dim_waktu.bulan_angka', (int)$currentMonth) // Cast ke int biar aman
            ->where('dim_waktu.tahun', $currentYear)
            ->sum('jumlah_barang_masuk') ?? 0;

        // Card 3: Kontribusi Saya (Barang yang berhasil dikembalikan oleh user ini)
        $myPerformance = DB::table('fact_pengembalian')
            ->where('validator_id', $userId)
            ->sum('jumlah_kembali') ?? 0;

        $stats = [
            'pending_tasks'    => $pendingTasks,
            'items_this_month' => $itemsInMonth,
            'my_performance'   => $myPerformance,
        ];

        // =================================================================
        // 2. DATA GRAFIK
        // =================================================================

        // A. STATUS PENYIMPANAN (Doughnut Chart)
        // Menggambarkan nasib barang temuan: Masih Pending, Disetujui (Disimpan), atau Sudah Kembali
        $warehouseComposition = DB::table('fact_penemuan')
            ->join('dim_status', 'fact_penemuan.status_id', '=', 'dim_status.id')
            ->select('dim_status.label_status as label', DB::raw('COUNT(*) as total'))
            ->groupBy('dim_status.label_status')
            ->get();

        // B. KATEGORI BARANG TEMUAN (Bar Chart)
        // Apa jenis barang yang paling sering ditemukan?
        $categoryDistribution = DB::table('fact_penemuan')
            ->join('dim_kategori', 'fact_penemuan.kategori_id', '=', 'dim_kategori.id')
            ->select('dim_kategori.nama_kategori as label', DB::raw('SUM(jumlah_barang_masuk) as total'))
            ->groupBy('dim_kategori.nama_kategori')
            ->orderByDesc('total')
            ->get();

        // =================================================================
        // 3. OPERATIONAL LISTS
        // =================================================================

        $recentReports = Report::with(['category', 'room.building', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['report.category', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'stats', 
            'warehouseComposition', 
            'categoryDistribution', 
            'recentReports', 
            'recentClaims'
        ));
    }

    // =================================================================
    // API UNTUK GRAFIK TREN (AJAX)
    // =================================================================
    public function getWarehouseTrend(Request $request)
    {
        $filter = $request->query('filter', 'daily');
        $year = date('Y');
        $month = (int)date('m');

        if ($filter == 'weekly') {
            $groupBy = 'dim_waktu.pekan_ke';
            $selectRaw = 'CONCAT("Pekan ", dim_waktu.pekan_ke) as label';
        } else { // daily
            $groupBy = 'dim_waktu.tanggal';
            // Ubah format tanggal jadi tgl-bulan biar ringkas (contoh: 15-Dec)
            $selectRaw = 'DATE_FORMAT(dim_waktu.tanggal, "%d-%b") as label';
        }

        $query = DB::table('fact_penemuan')
            ->join('dim_waktu', 'fact_penemuan.waktu_id', '=', 'dim_waktu.id')
            ->select(DB::raw($selectRaw), DB::raw('SUM(jumlah_barang_masuk) as total'))
            ->groupBy(DB::raw($groupBy));

        // Filter Tahun Ini
        $query->where('dim_waktu.tahun', $year);
        
        // Kalau Harian, filter bulan ini saja biar grafik gak kepanjangan
        if ($filter == 'daily') {
            $query->where('dim_waktu.bulan_angka', $month);
        }

        $data = $query->orderBy(DB::raw($groupBy))->get();

        // Handle jika data kosong (biar grafik tetap muncul tapi flat)
        if ($data->isEmpty() && $filter == 'daily') {
             // Opsional: Return array kosong, nanti JS yang handle
        }

        return response()->json([
            'labels' => $data->pluck('label'),
            'data'   => $data->pluck('total')
        ]);
    }

    // --- METHOD LAINNYA TETAP SAMA SEPERTI SEBELUMNYA ---
    // (createReport, storeReport, edit, update, validate, dll)
    // Pastikan method-method CRUD lainnya tetap ada di bawah sini
    
    public function reports(Request $request)
    {
        $query = Report::with(['category', 'room.building', 'user'])
            ->whereIn('status', ['pending', 'approved', 'rejected']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $search = $request->search;
                $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $stats = [
            'pending_total' => Report::where('status', 'pending')->count(), 
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'lost' => (clone $query)->where('type', 'lost')->count(),
            'found' => (clone $query)->where('type', 'found')->count(),
        ];
        
        $reports = $query->latest('updated_at')->paginate(10);

        return view('shared.reports_validation', compact('reports', 'stats'));
    }

    public function validateReport(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $report->update([
            'status' => $request->status,
            'validator_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil divalidasi');
    }

    public function claims(Request $request)
    {
        $query = Claim::with(['user', 'report'])
            ->whereIn('status', ['pending', 'approved', 'rejected']);

        $stats = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('report', fn($q2) => $q2->where('item_name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }
        $claims = $query->latest('updated_at')->paginate(10);

        return view('shared.claims_validation', compact('claims'));
    }

    public function validateClaim(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $claim->update([
            'status' => $request->status,
            'validator_id' => auth()->id(),
        ]);

        if ($request->status === 'approved') {
            $claim->report->update(['status' => 'returned']);
        }

        return redirect()->back()->with('success', 'Klaim berhasil divalidasi');
    }

    public function createReport()
    {
        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        return view('petugas.reports.create', compact('categories', 'buildings'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'event_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'room_id' => $request->room_id,
            'event_date' => $request->event_date,
            'photo' => $imagePath,
            'type' => 'found',
            'status' => 'approved',
            'validator_id' => auth()->id(),
        ]);

        return redirect()->route('petugas.reports')
            ->with('success', 'Laporan berhasil ditambahkan dan langsung disetujui.');
    }

    public function showReport(Report $report)
    {
        $this->authorize('view', $report);
        $report->load(['user', 'room.building', 'category', 'validator', 'claims.user']);
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        return view('petugas.reports.edit', compact('report', 'categories', 'buildings'));
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'event_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($report->photo) {
                Storage::disk('public')->delete($report->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('reports', 'public');
        }
        
        $report->update($validatedData);

        return redirect()->route('petugas.reports')->with('success', 'Laporan berhasil diperbarui.');
    }
}