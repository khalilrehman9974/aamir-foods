<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use Illuminate\Database\Seeder;

class FinancialYears extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'name' => '2023',
                'start_date' => '2023-01-01',
                'end_date' => '2023-12-01',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => '2024',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-01',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => '2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-01',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => '2026',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-01',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => '2027',
                'start_date' => '2027-01-01',
                'end_date' => '2027-12-01',
                'created_by' => 1,
                'updated_by' => 1,
            ],

        ];

        foreach($menus as $area){
            FinancialYear::create($area);
        }
    }
}
