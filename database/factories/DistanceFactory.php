<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distance>
 */
class DistanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sourceLocation = DB::table('locations')->inRandomOrder()->first();
        $destinationLocation = DB::table('locations')
            ->where('id', '!=', $sourceLocation->id)
            ->inRandomOrder()
            ->first();
        return [
            'source_location' => $sourceLocation->id,
            'destination_location' => $destinationLocation->id,
            'distance' => $this->faker->numberBetween(5,3000)
        ];
    }
}
