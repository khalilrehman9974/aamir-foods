<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalePurchaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salePurchasetypes = [
            [
                'name' => 'Sale',
            ],
            [
                'name' => 'Sale Return',
            ],
            [
                'name' => 'Purchase',
            ],
            [
                'name' => 'Purchase Return',
            ]
        ];

        foreach($salePurchasetypes as $type){
            \App\Models\SalePurchaseType::create($type);
        }
    }
}
