<?php

namespace Database\Factories;

use App\Models\SaleMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleDetail>
 */
class SaleDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sale_master_id' => SaleMaster::factory(), // will create related sale master automatically
            'product_id' => rand(1, 100),
            'packing_type' => $this->faker->randomElement(['Boray', 'Carton']),
            'measurement_type' => 'kg',
            'soQuantity' => rand(10, 100),
            'dispQuantity' => rand(5, 100),
            'quantity' => rand(1, 100),
            'dzns' => rand(1, 10),
            'total_dzns' => rand(10, 100),
            'rate' => rand(50, 500),
            'discount' => rand(0, 10),
            'amount' => rand(100, 1000),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
