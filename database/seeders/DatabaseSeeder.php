<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create a superAdmin user
         \App\Models\User::factory()->create(
            [
                    'name' => 'superAdmin',
                    'email' => 'superadmin@admin.com',
                    'password' => bcrypt('12345678'),
                    'userType' => 'superadmin',
                    'userStatus' => 'active'
            ],
         );

        // Create an admin user
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'userType' => 'admin',
            'userStatus' => 'active'
        ]);

        // Create a regular user
        \App\Models\User::factory()->create([
            'name' => 'user',
            'email' => 'user@admin.com',
            'password' => bcrypt('12345678'),
            'userType' => 'user',
            'userStatus' => 'active'
        ]);
    }
}
