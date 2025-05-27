<?php

namespace Database\Factories;

use App\Models\SaleMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleMaster>
 */
class SaleMasterFactory extends Factory
{
    protected $model = SaleMaster::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
              'dispatch_note_number' => $this->faker->unique()->numerify('DN-####'),
            'sale_order_number' => $this->faker->unique()->numerify('SO-####'),
            'date' => $this->faker->date(),
            'party_id' => 2,
            'sector' => $this->faker->numberBetween(1, 4),
            'area' => $this->faker->numberBetween(1, 8),
            'delivered_to' => $this->faker->numberBetween(1, 4),
            'transporter_id' => 1,
            'saleman' => $this->faker->numberBetween(1, 6),
            'vehicle_no' => strtoupper($this->faker->bothify('ABC-####')),
            'business_id' => $this->faker->numberBetween(1, 2),
            'f_year_id' => 1,
            'driver_name' => $this->faker->name(),
            'bilty_no' => $this->faker->unique()->numerify('BILTY-####'),
            'remarks' => $this->faker->sentence(),
            'total_boray' => $this->faker->randomFloat(2, 0, 1000),
            'total_carton' => $this->faker->randomFloat(2, 0, 1000),
            'gross_bill' => $this->faker->randomFloat(2, 1000, 50000),
            'carriage' => $this->faker->randomFloat(2, 100, 2000),
            'totaldiscount' => $this->faker->randomFloat(2, 0, 500),
            'commission' => $this->faker->randomFloat(2, 0, 300),
            'net_amount' => $this->faker->randomFloat(2, 1000, 60000),
            'created_by' => $this->faker->numberBetween(1, 10),
            'updated_by' => $this->faker->numberBetween(1, 10),
        ];
    }
}
