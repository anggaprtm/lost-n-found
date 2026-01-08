<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('claim_id')->index();
            $table->unsignedBigInteger('report_id')->index();
            $table->foreignId('waktu_id')->constrained('dim_waktu');
            $table->unsignedBigInteger('validator_id')->index(); // This is the user ID from users table
            $table->integer('jumlah_kembali')->default(1);
            $table->timestamps();

            $table->foreign('claim_id')->references('id')->on('claims')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('validator_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Backfill data from existing approved claims
        $approvedClaims = DB::table('claims')->where('status', 'approved')->get();
        $now = Carbon::now();

        foreach ($approvedClaims as $claim) {
            // Ensure validator_id is not null before proceeding
            if (is_null($claim->validator_id) || is_null($claim->updated_at)) {
                continue;
            }

            $claimDate = Carbon::parse($claim->updated_at);

            // Find or create dim_waktu entry
            $waktuId = DB::table('dim_waktu')->where('tanggal', $claimDate->toDateString())->value('id');
            if (!$waktuId) {
                $waktuId = DB::table('dim_waktu')->insertGetId([
                    'tanggal'     => $claimDate->toDateString(),
                    'hari_ke'     => $claimDate->day,
                    'hari_nama'   => $claimDate->format('l'),
                    'bulan_angka' => $claimDate->month,
                    'bulan_nama'  => $claimDate->format('F'),
                    'tahun'       => $claimDate->year,
                    'pekan_ke'    => $claimDate->weekOfYear,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }

            // Insert into fact_pengembalian, but check for existence first
            $exists = DB::table('fact_pengembalian')->where('claim_id', $claim->id)->exists();
            if (!$exists) {
                DB::table('fact_pengembalian')->insert([
                    'claim_id'       => $claim->id,
                    'report_id'      => $claim->report_id,
                    'waktu_id'       => $waktuId,
                    'validator_id'   => $claim->validator_id,
                    'jumlah_kembali' => 1,
                    'created_at'     => $claim->updated_at,
                    'updated_at'     => $claim->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fact_pengembalian');
    }
};