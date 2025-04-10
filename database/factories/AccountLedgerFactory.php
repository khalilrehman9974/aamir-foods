<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountLedger>
 */
class AccountLedgerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'invoice_id' => $this->faker->numberBetween(1000, 9999),
            'party_id' => 45,
            'description' => $this->faker->sentence(4),
            'document_number' => 'DOC-' . $this->faker->unique()->numerify('#####'),
            'bags' => $this->faker->numberBetween(1, 50),
            'measurementType' => $this->faker->randomElement([1, 2]),
            'total_quantity' => $this->faker->numberBetween(10, 500),
            'transporter_id' => \App\Models\Transporter::inRandomOrder()->value('id') ?? null,
            'bilty_no' => $this->faker->bothify('BILTY-####'),
            'rate' => $this->faker->numberBetween(100, 1000),
            'debit' => $this->faker->randomFloat(2, 1000, 5000),
            'credit' => $this->faker->randomFloat(2, 500, 3000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
