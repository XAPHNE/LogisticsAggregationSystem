<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\Fleet;
use App\Models\Location;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fleet>
 */
class FleetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owned_by' => function () {
                $user = User::factory()->create();
                $user->assignRole('Fleet Owner');
                return $user->id;
            },
            'driven_by' => function () {
                $user = User::factory()->create();
                $user->assignRole('Driver');
                return $user->id;
            },
            'category_id' => function () {
                $usedParentIds = DB::table('categories')->whereNotNull('parent_id')->pluck('parent_id')->toArray();

                // Select only IDs that are not used as a parent_id
                $availableIds = DB::table('categories')->where(function ($query) use ($usedParentIds) {
                    $query->whereNotIn('id', $usedParentIds)
                        ->orWhereNull('parent_id');
                })->pluck('id')->toArray();

                return count($availableIds) > 0 ? Arr::random($availableIds) : null;
            },

            'registration_num' => function () {
                $stateInitials = ['AS', 'WB', 'NL', 'TN', 'KA', 'MH', 'ML'];
                $district = str_pad(random_int(1, 28), 2, '0', STR_PAD_LEFT);
                $series = chr(random_int(65, 90)) . chr(random_int(65, 90)) . chr(random_int(65, 90)) . chr(random_int(65, 90));
                $randomDigits = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);

                return $stateInitials[array_rand($stateInitials)] . $district . $series . $randomDigits;
            },
            'permit_type' => $this->faker->randomElement(['All India', 'All Assam']),
            'insurance_expiry' => $this->faker->dateTimeBetween('now', '+1 year'),
            'pollution_expiry' => $this->faker->dateTimeBetween('now', '+6 month'),
            'fitness_expiry' => $this->faker->dateTimeBetween('now', '+15 year'),
            'current_location' => function () {
                return Location::factory()->create()->id;
            },
            'max_height' => $this->faker->numberBetween( 1500, 1800),
            'max_length' => $this->faker->numberBetween(2000, 2200),
            'max_width' => $this->faker->numberBetween(1500, 1800),
            'available_height' => $this->faker->numberBetween( 1500, 1800),
            'available_length' => $this->faker->numberBetween(2000, 2200),
            'available_width' => $this->faker->numberBetween(1500, 1800),
            'status' => $this->faker->randomElement(['Available', 'Assigned']),
        ];
    }
}
