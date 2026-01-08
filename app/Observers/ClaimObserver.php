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
            
            // Logika insert ke fact_pengembalian dihapus karena tabel sudah ditiadakan.
        }
    }
}