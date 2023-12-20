<?php

namespace Database\Factories;

use App\Models\Distance;
use App\Models\Fleet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source_location' => function () {
                return Distance::inRandomOrder()->first()->source_location;
            },
            'destination_location' => function (array $attributes) {
                $sourceLocation = $attributes['source_location'];

                // Select a destination_location from distances where source_location matches
                return Distance::where('source_location', $sourceLocation)
                    ->inRandomOrder()
                    ->first()
                    ->destination_location;
            },
            'distance' => function (array $attributes) {
                $sourceLocation = $attributes['source_location'];
                $destinationLocation = $attributes['destination_location'];

                // Select distance value from distances where source_location and destination_location match
                return Distance::where('source_location', $sourceLocation)
                    ->where('destination_location', $destinationLocation)
                    ->value('distance');
            },
            'fleet_id' => function () {
                // 50% chance of being null
                return optional(Fleet::factory()->create())->id;
            },
            'weight' => $this->faker->randomFloat(2, 100, 1000),
            'load_at' => $this->faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
            'price' => function (array $attributes) {
                $distance = $attributes['distance'];
                $weight = $attributes['weight'];

                $calculatedPrice = ($distance * 4) + ($weight * 2);

                // Check if the calculated price is less than 2000
                return max($calculatedPrice, 2000);
            },
            'status' => $this->faker->randomElement(['Open', 'Accepted', 'Transit', 'Completed', 'Cancelled']),
            'order_placed_by' => function () {
                $user = User::factory()->create();
                $user->assignRole('Customer');
                return $user->id;
            },
        ];
    }
}
