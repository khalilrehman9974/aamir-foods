<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
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
            ],
            [
                'name' => 'Products',
            ],
            [
                'name' => 'User Registration',
            ],
            [
                'name' => 'Brands',
            ],
            [
                'name' => 'Accounts',
            ],
            [
                'name' => 'Customers',
            ],
            [
                'name' => 'Bank Registration',
            ],
            [
                'name' => 'Financial Year',
            ],
            [
                'name' => 'Add Area'
            ],
            [
                'name' => 'Assign Area'
            ],
            [
                'name' => 'User Rights'
            ],
            [
                'name' => 'Categories'
            ],
            [
                'name' => 'Delivery Charges'
            ],
            [
                'name' => 'Party '
            ],
            [
                'name' => 'Purchase'
            ],
            [
                'name' => 'Purchase Returns'
            ],
            [
                'name' => 'Sales'
            ],
            [
                'name' => 'Sale Returns'
            ],
            [
                'name' => 'customerPrice'
            ]
        ];

        foreach($menus as $area){
            \App\Models\Menu::create($area);
        }
    }
}
