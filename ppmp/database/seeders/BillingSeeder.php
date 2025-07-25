<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lots = \App\Models\Lot::all(); // Assuming you have a Lot model to get all lots
        $startDate = Carbon::create(2023, 1, 1); // Starting from January 2023
        $endDate = Carbon::create(2025, 12, 31); // Ending in December 2025

        foreach ($lots as $lot) {
            // Generate monthly billings for each lot from January 2023 to December 2025
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $amountDue = $lot->lot_area * 2.50; // Calculate amount due based on lot area

                DB::table('billings')->insert([
                    'lot_id' => $lot->id,
                    'billing_date' => $currentDate->firstOfMonth()->toDateString(),
                    'amount_due' => $amountDue,
                    'amount_paid' => 0.00, // Initially no payment
                    'balance' => $amountDue,
                    'status' => 'Unpaid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Move to the next month
                $currentDate->addMonth();
            }
        }
    }
}
