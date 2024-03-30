<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vouchertypes = [
            [
                'name' => 'BRV',
            ],
            [
                'name' => 'BPV',
            ],
            [
                'name' => 'CPV',
            ],
            [
                'name' => 'CRV',
            ],
            [
                'name' => 'JV',
            ]
        ];

        foreach($vouchertypes as $type){
            \App\Models\VoucherType::create($type);
        }
    }
}
