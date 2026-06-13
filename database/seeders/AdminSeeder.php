<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rishabh@admin.com'],
            [
                'name' => 'Rishabh Parekh',
                'password' => '12345678',
                'email_verified_at' => now(),
            ]
        );
    }
}
