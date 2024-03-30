<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(100)->create();

//        Product::insert([
//            'name' => 'iphone',
//            'price' => 1000.98,
//            'sale_price' => 1200.98,
//            'created_by' => 'admin',
//            'updated_by' => 'admin',
//
//        ]);
//
//        Product::insert([
//            'name' => 'Galaxy',
//            'price' => 2000.50,
//            'sale_price' => 2246.98,
//            'created_by' => 'sadmin',
//            'updated_by' => 'sadmin',
//        ]);
//
//        Product::insert([
//            'name' => 'Sony',
//            'price' => 3000,
//            'sale_price' => 3346.98,
//            'created_by' => 'adminadmin',
//            'updated_by' => 'adminadmin',
//        ]);
    }
}
