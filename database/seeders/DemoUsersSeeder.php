<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($number = 1; $number <= 10; $number++) {
            $username = 'user'.$number;

            User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => 'User '.$number,
                    'email' => $username.'@example.com',
                    'password' => 'password',
                    'status' => true,
                    'role' => User::ROLE_USER,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
