<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $demoUser = User::create([
            'name' => $faker->firstName,
            'uuid' => Str::uuid(),
            'email' => 'admin@admin.com',
            'password' => Hash::make('Admin@123'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'timezone' => env('TIMEZONE'),
            'role' => 1,
            'is_db_created' => 1,
            'is_first_login' => 1,
        ]);

    }
}
