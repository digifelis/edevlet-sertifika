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

        /* create kurum from kurumModal */
        \App\Models\superadmin\KurumModal::factory(10)->create();
        /* create kurs from kursModal */
        \App\Models\superadmin\KursModal::factory(10)->create();
        /* creeate ogrenciler from ogrencilerModal */
        \App\Models\superadmin\OgrencilerModal::factory(100)->create();
        /* create sertifikalar from sertifikalarModal */
        \App\Models\superadmin\SertifikalarModal::factory(100)->create();






    }
}
