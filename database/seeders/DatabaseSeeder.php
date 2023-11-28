<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory()->create([
             'id' => '1',
             'name' => 'levon',
             'email' => 'lyov.karamyan@gmail.com',
             'email_verified_at' => '2023-11-25 23:34:48',
             'type' => 'seller',
             'password' => '$2y$12$yos.jcK07OuyWvBKwKn8luSqdt1liOskMvYSnEa0pA/db..4cmZz2',
         ]);

         DB::table('oauth_clients')->insert([
             'id' => '1',
             'user_id' => '1',
             'name' => 'levon',
             'secret' => 'Tx39OMC3AVO4MmWQIXJ6zak2Jcos4Jb2eIbIMuTJ',
             'redirect' => 'http://localhost/auth/callback',
             'personal_access_client' => '1',
             'password_client' => '1',
             'revoked' => 0,
         ]);

        \App\Models\Product::insert([
            'id' => '1',
            'name' => 'product 1',
            'user_id' => '1',
            'price' => '10',
            'rent_price' => '5',
            'product_count' => '5'
        ]);

        \App\Models\Product::insert([
            'id' => '2',
            'name' => 'product 2',
            'user_id' => '1',
            'price' => '7',
            'rent_price' => '3',
            'product_count' => '4'
        ]);
    }
}
