<?php

namespace Database\Factories;

use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trackingCodePrefix = ['TH', 'NL', 'GB', 'CX'];

        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->boolean() ? $this->faker->sentence(4) : null,
            'tracking_code' => sprintf(
                '%s%sBR',
                $this->faker->randomElement($trackingCodePrefix),
                $this->faker->randomNumber(9)
            ),
            'status' => $this->faker->randomElement(PackageStatus::cases()),
            'last_tracked_at' => $this->faker->dateTimeBetween('-1 month'),
        ];
    }
}
