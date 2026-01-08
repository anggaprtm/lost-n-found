<?php

namespace App\Observers;

use App\Models\Claim;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClaimObserver
{
    /**
     * Handle the Claim "created" event.
     *
     * @param  \App\Models\Claim  $claim
     * @return void
     */
    public function created(Claim $claim)
    {
        // Jika klaim DIBUAT dan statusnya sudah 'approved' (misal: via assign)
        if ($claim->status === 'approved') {
            $this->handleApprovedClaim($claim);
        }
    }

    /**
     * Handle the Claim "updated" event.
     *
     * @param  \App\Models\Claim  $claim
     * @return void
     */
    public function updated(Claim $claim)
    {
        // Cek: Apakah status BERUBAH jadi 'approved'?
        if ($claim->isDirty('status') && $claim->status === 'approved') {
            $this->handleApprovedClaim($claim);
        }
    }

    /**
     * Handles the logic for an approved claim.
     *
     * @param Claim $claim
     */
    public function handleApprovedClaim(Claim $claim)
    {
        // 1. Update status laporan terkait menjadi 'returned'
        $claim->report()->update(['status' => 'returned']);

        // 2. Dapatkan atau buat ID Waktu dari `dim_waktu`
        $now = Carbon::now();
        $waktuId = DB::table('dim_waktu')->where('tanggal', $now->toDateString())->value('id');

        if (!$waktuId) {
            $waktuId = DB::table('dim_waktu')->insertGetId([
                'tanggal'     => $now->toDateString(),
                'hari_ke'     => $now->day,
                'hari_nama'   => $now->format('l'),
                'bulan_angka' => $now->month,
                'bulan_nama'  => $now->format('F'),
                'tahun'       => $now->year,
                'pekan_ke'    => $now->weekOfYear,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        // 3. Masukkan data ke dalam `fact_pengembalian`, pastikan tidak duplikat
        $exists = DB::table('fact_pengembalian')->where('claim_id', $claim->id)->exists();
        if (!$exists) {
            DB::table('fact_pengembalian')->insert([
                'claim_id'       => $claim->id,
                'report_id'      => $claim->report_id,
                'waktu_id'       => $waktuId,
                'validator_id'   => $claim->validator_id,
                'jumlah_kembali' => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }
}