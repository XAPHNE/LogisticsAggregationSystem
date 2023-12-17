<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         $user1 = User::factory()->create([
             'name' => 'Admin User',
             'email' => 'admin@last.com',
         ]);
        $user2 = User::factory()->create([
            'name' => 'Employee User',
            'email' => 'employee@last.com',
        ]);
        $user3 = User::factory()->create([
            'name' => 'Driver User',
            'email' => 'driver@last.com',
        ]);
        $user4 = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@last.com',
        ]);
        $role1 = Role::create(['name' => 'Administrator']);
        $user1->assignRole($role1);
        $role2 = Role::create(['name' => 'Employee']);
        $user2->assignRole($role2);
        $role3 = Role::create(['name' => 'Driver']);
        $user3->assignRole($role3);
        $role4 = Role::create(['name' => 'Customer']);
        $user4->assignRole($role4);
    }
}
