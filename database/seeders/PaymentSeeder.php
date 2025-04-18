<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = Candidate::whereHas('regime', function ($query) {
            $query->where('name', 'Laboral');
        })->get();

        foreach ($candidates as $candidate) {
            Payment::create([
                'candidate_id' => $candidate->id,
                'doc' => null,
                'value' => '1000.00',
                'entity' => '12345',
                'reference' => strtoupper(Str::random(10)),
                'status' => 1,
                'date_payment' => Carbon::now(),
            ]);
        }
    }
}
