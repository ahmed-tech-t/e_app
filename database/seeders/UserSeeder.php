<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL') ?: config('auth.admin.email');
        $password = env('ADMIN_PASSWORD') ?: config('auth.admin.password');

        if ($email && $password) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => env('ADMIN_NAME', 'Admin Rashed'),
                    'password' => Hash::make($password),
                ]
            );
        }
    }
}
