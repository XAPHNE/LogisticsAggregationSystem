<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Distance;
use App\Models\Fleet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         $user1 = User::factory()->create([     //  Initial Users created with roles for testing
             'name' => 'Admin User',
             'email' => 'admin@last.com',
         ]);
        $user2 = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@last.com',
        ]);
        $user3 = User::factory()->create([
            'name' => 'Fleet Owner',
            'email' => 'owner@last.com',
        ]);
        $user4 = User::factory()->create([
            'name' => 'Driver User',
            'email' => 'driver@last.com',
        ]);
        $user5 = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@last.com',
        ]);
        $role1 = Role::create(['name' => 'Administrator']);
        $user1->assignRole($role1);
        $role2 = Role::create(['name' => 'Employee']);
        $user2->assignRole($role2);
        $role3 = Role::create(['name' => 'Fleet Owner']);
        $user3->assignRole($role3);
        $role4 = Role::create(['name' => 'Driver']);
        $user4->assignRole($role4);
        $role5 = Role::create(['name' => 'Customer']);
        $user5->assignRole($role5);
//      -----------------------END----------------------------

        //  Initial Categories created for testing
        $parentCategories = [
            ['name' => 'Trucks', 'description' => 'This is a category of vehicles which come under the class truck', 'parent_id' => null],
        ];

        foreach ($parentCategories as $categoryData) {
            $parentCategory = new Category($categoryData);
            $parentCategory->save();

            // Insert child categories for each parent
            $childCategories = [
                ['name' => 'Four Wheel Trucks', 'description' => 'This is a category of trucks which have only four wheels', 'parent_id' => $parentCategory->id],
            ];

            foreach ($childCategories as $childCategoryData) {
                $childCategory = new Category($childCategoryData);
                $childCategory->save();

                // Insert grandchild categories for each child
                $grandChildCategories = [
                    ['name' => 'Tata Ace', 'description' => 'This is a small 4-wheel truck made by Tata', 'parent_id' => $childCategory->id],
                    ['name' => 'Mahindra Jeeto', 'description' => 'This is a small 4-wheel truck made by Mahindra', 'parent_id' => $childCategory->id],
                ];

                foreach ($grandChildCategories as $grandChildCategoryData) {
                    $grandChildCategory = new Category($grandChildCategoryData);
                    $grandChildCategory->save();
                }
            }
        }
//      ----------------------------END-----------------------------------

        Fleet::factory()->count(50)->create();
        Distance::factory()->count(50)->create();
    }
}
