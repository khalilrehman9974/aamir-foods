<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->unique()->numberBetween([1,100]),
            'sale_price' =>$this->faker->numberBetween([1,100]),
            'created_by' => $this->faker->unique()->userName(),
            'updated_by' => $this->faker->unique()->userName(),
        ];
    }
}
