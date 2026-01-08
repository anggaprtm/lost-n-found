<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    // =================================================
    // == FUNGSI UNTUK PENGUNJUNG (PUBLIC) ==
    // =================================================
    use AuthorizesRequests; // <-- 2. TAMBAHKAN INI

    /**
     * Menampilkan Landing Page lengkap (Hero, FAQ, dll. + daftar laporan).
     * Route: GET / -> name('landing')
     */
    public function publicIndex(Request $request)
    {
        $query = Report::with(['user', 'room.building', 'category'])
            ->where('status', 'approved');

        // Logika filter dari kodemu yang sudah bagus
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('building')) {
            $query->whereHas('room', fn($q) => $q->where('building_id', $request->building));
        }

        $reports = $query->latest()->paginate(9);
        $categories = Category::all();
        $buildings = Building::all();

        // Mengarah ke file view landing page utama (welcome.blade.php)
        return view('welcome', compact('reports', 'categories', 'buildings'));
    }
    
    /**
     * == INI METHOD BARU ==
     * Menampilkan HANYA daftar laporan publik.
     * Route: GET /reports/public -> name('reports.public_index')
     */
    public function publicReportsIndex(Request $request)
    {
        // Logikanya sama persis dengan publicIndex
        $query = Report::with(['user', 'room.building', 'category'])
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('building')) {
            $query->whereHas('room', fn($q) => $q->where('building_id', $request->building));
        }
        
        $reports = $query->latest()->paginate(12); // Bisa lebih banyak di halaman ini
        $categories = Category::all();
        $buildings = Building::all();

        // Mengarah ke file view baru yang akan kita buat
        return view('reports.public_index', compact('reports', 'categories', 'buildings'));
    }

    // =================================================
    // == FUNGSI UNTUK PENGGUNA (ROLE: PENGGUNA) ==
    // =================================================

    /**
     * Menampilkan daftar laporan milik pengguna yang login ("Laporan Saya").
     * Route: GET /reports -> name('reports.index')
     */
    public function index(Request $request)
    {
        $query = Report::with(['user', 'room.building', 'category'])
            ->where('user_id', Auth::id());

        // ... (logika filter sama seperti yang kamu buat) ...
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('type')) { $query->where('type', $request->type); }

        $reports = $query->latest()->paginate(10);
        return view('reports.index', compact('reports'));
    }

    /**
     * Menampilkan formulir untuk membuat laporan baru.
     * Route: GET /reports/create -> name('reports.create')
     */
    public function create()
    {
        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        return view('reports.create', compact('categories', 'buildings'));
    }

    /**
     * Menyimpan laporan baru ke database.
     * Route: POST /reports -> name('reports.store')
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'type' => 'required|in:lost,found',
            'event_date' => 'required|date|before_or_equal:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        
        // Jika Admin atau Petugas yang buat, langsung approved
        if (in_array(Auth::user()->role, ['admin', 'petugas'])) {
            $validated['status'] = 'approved';
        } else {
            $validated['status'] = 'pending';
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('reports', 'public');
        }

        Report::create($validated);

        $message = $validated['status'] === 'approved' ? 'Laporan berhasil dibuat.' : 'Laporan berhasil dibuat dan menunggu validasi.';

        return redirect()->route('reports.index')->with('success', $message);
    }

    public function edit(Report $report)
    {

        // PERBAIKAN: Gunakan Policy untuk otorisasi yang fleksibel
        // Bypass otorisasi jika user adalah Admin atau Petugas
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            $this->authorize('update', $report);
        }

        $categories = Category::all();
        $buildings = Building::with('rooms')->get();
        
        // Pastikan action form di view 'edit' mengarah ke 'petugas.reports.update'
        return view('petugas.reports.edit', compact('report', 'categories', 'buildings'));
    }

    public function update(Request $request, Report $report)
    {

        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            $this->authorize('update', $report);
        }

        // Validasi data, sama seperti di method store()
        $validatedData = $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'room_id' => 'required|exists:rooms,id',
            'event_date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|min:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload foto baru (jika ada)
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($report->photo) {
                Storage::disk('public')->delete($report->photo);
            }

            $validatedData['photo'] = $request->file('photo')->store('reports', 'public');
        }

        // Update data laporan di database
        $report->update($validatedData);

        if (Auth::user()->role === 'pengguna') {
            $redirectRoute = 'reports.show';
        } elseif (Auth::user()->role === 'admin') {
            $redirectRoute = 'admin.reports.show';
        } else {
            $redirectRoute = 'petugas.reports.show';
        }

        return redirect()->route($redirectRoute, $report)->with('success', 'Laporan berhasil diperbarui.');
    }
    /**
     * Menampilkan detail satu laporan.
     * Route: GET /reports/{report} -> name('reports.show')
     */
    public function show(Report $report)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            $this->authorize('view', $report);
        }

        $report->load(['user', 'room.building', 'category', 'validator', 'claims.user']);
        return view('reports.show', compact('report'));
    }
    public function destroy(Report $report)
    {
        // Otorisasi sederhana untuk memastikan hanya role tertentu yang bisa menghapus
        if (!in_array(Auth::user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        // Hapus foto dari storage jika ada
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }

        // Hapus data laporan dari database
        $report->delete();

        // Arahkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }


    public function validationPage(Request $request)
    {

        $query = Report::with(['category', 'room.building', 'user'])
        ->where('status', 'pending');

        $reports = $query->latest()->paginate(10);

        // Arahkan ke view yang benar (shared/reports_validation.blade.php)
        return view('shared.reports_validation', compact('reports'));
    }
    // Method edit, update, destroy, dll. milikmu bisa diletakkan di sini.
    // Kode untuk edit dan update yang kamu buat sudah bagus.

    public function validation(Request $request, Report $report)
    {

        $request->validate([
        'status' => 'required|in:approved,rejected',
        'notes'  => 'nullable|string',
    ]);

    $report->update([
        'status' => $request->status,
        'notes'  => $request->notes,
        'validator_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Laporan berhasil divalidasi');
    }

}