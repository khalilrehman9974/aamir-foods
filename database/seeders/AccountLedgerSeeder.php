<?php

namespace Database\Seeders;

use App\Models\AccountLedger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total = 100000; // Number of entries
        $chunkSize = 10000;

        for ($i = 0; $i < $total / $chunkSize; $i++) {
            AccountLedger::factory()->count($chunkSize)->create();
        }
    }
}
