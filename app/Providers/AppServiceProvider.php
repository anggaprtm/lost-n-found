<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// --- BAGIAN 1: PANGGIL FILE MODEL & OBSERVER ---
use App\Models\Report;
use App\Models\Claim;
use App\Observers\ReportObserver;
use App\Observers\ClaimObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- BAGIAN 2: AKTIFKAN OBSERVER DISINI ---
        
        // "Eh Laravel, tolong pantau model Report pake logika ReportObserver ya!"
        Report::observe(ReportObserver::class);

        // "Pantau juga model Claim pake ClaimObserver!"
        Claim::observe(ClaimObserver::class);
    }
}