<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $query = Claim::with(['report.category'])
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->whereHas('report', function ($q) use ($request) {
                $q->where('item_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->latest()->paginate(10);
        return view('claims.index', compact('claims'));
    }

    public function show(Claim $claim)
    {
        $claim->load(['report.category', 'report.room.building', 'report.user', 'validator']);
        return view('claims.show', compact('claim'));
    }

    public function store(Request $request)
    {
        /** 
         * VALIDASI WAJIB DILETAKKAN PALING ATAS
         * Jika tidak, klaim gagal tapi tidak kelihatan errornya
         */
        $request->validate([
            'report_id'   => 'required|exists:reports,id',
            'description' => 'required|string|min:1',
        ]);

        // Ambil report
        $report = Report::findOrFail($request->report_id);

        // Hanya report found + approved yang bisa diklaim
        if ($report->status !== 'approved' || $report->type !== 'found') {
            return back()->with('error', 'Barang ini tidak dapat diklaim.');
        }

        // User tidak boleh klaim barang miliknya sendiri (kecuali laporan dibuat admin/petugas)
        if ($report->user_id === Auth::id() && !in_array($report->user->role, ['admin', 'petugas'])) {
            return back()->with('error', 'Anda tidak dapat mengklaim barang yang Anda laporkan sendiri.');
        }

        // Cek apakah user sudah punya klaim pending
        $existingClaim = Claim::where('report_id', $report->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingClaim) {
            return back()->with('error', 'Anda sudah memiliki klaim yang sedang diproses untuk barang ini.');
        }

        // Simpan klaim
        Claim::create([
            'report_id'   => $report->id,
            'user_id'     => Auth::id(),
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        // Notifikasi ke pemilik laporan
        $report->user->notifications()->create([
            'title'   => 'Klaim Baru untuk Barang Anda',
            'message' => "Ada klaim baru untuk barang '{$report->item_name}'. Silakan cek panel petugas.",
        ]);

        return redirect()
        ->route('reports.show', $report->id)
        ->with('success', 'Klaim berhasil diajukan. Menunggu validasi dari petugas.');

    }

    public function validation()
    {
        $query = Claim::with(['report.category', 'report.room.building', 'user'])
            ->where('status', 'pending');

        if (request('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('report', function ($q2) use ($search) {
                    $q2->where('item_name', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                });
            });
        }

        $claims = $query->latest()->paginate(10);
        return view('shared.claims_validation', compact('claims'));
    }

    public function validateClaim(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes'  => 'nullable|string',
        ]);

        // Update claim
        $claim->update([
            'status'       => $request->status,
            'validator_id' => Auth::id(),
        ]);

        // Jika disetujui, ubah report menjadi returned
        if ($request->status === 'approved') {
            $claim->report->update(['status' => 'returned']);
        }

        // Notifikasi untuk pengklaim
        $claim->user->notifications()->create([
            'title'   => $request->status === 'approved' ? 'Klaim Disetujui!' : 'Klaim Ditolak',
            'message' => $request->status === 'approved'
                ? "Klaim Anda untuk barang '{$claim->report->item_name}' telah disetujui."
                : "Klaim Anda ditolak." . ($request->notes ? " Alasan: {$request->notes}" : ""),
        ]);

        // Notifikasi ke pemilik laporan
        $claim->report->user->notifications()->create([
            'title'   => 'Status Klaim Diperbarui',
            'message' => "Klaim untuk barang '{$claim->report->item_name}' telah " .
                ($request->status === 'approved' ? 'disetujui' : 'ditolak') . " oleh petugas.",
        ]);

        return back()->with('success', 'Klaim berhasil divalidasi.');
    }

    public function validated(Request $request)
    {
        $request->merge(['status' => 'approved']);
        return $this->index();
    }

    public function destroy(Claim $claim)
    {
        if (!in_array(Auth::user()->role, ['petugas', 'admin'])) {
            abort(403);
        }

        if ($claim->status === 'approved') {
            $claim->report->update(['status' => 'claimed']);
        }

        $claim->delete();

        return back()->with('success', 'Klaim berhasil dihapus.');
    }
}
