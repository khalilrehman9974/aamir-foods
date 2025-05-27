<?php

namespace Database\Seeders;

use App\Models\SaleDetail;
use App\Models\SaleMaster;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Adjust batch size to prevent memory issues
        $batchSize = 1000;
        $totalRecords = 100000;

        for ($i = 0; $i < $totalRecords / $batchSize; $i++) {
            $masters = SaleMaster::factory()->count($batchSize)->create();

            foreach ($masters as $master) {
                // Add 1 to 5 sale details per master
                $detailsCount = rand(1, 5);
                SaleDetail::factory()
                    ->count($detailsCount)
                    ->create(['sale_master_id' => $master->id]);
            }
        }
    }
}
