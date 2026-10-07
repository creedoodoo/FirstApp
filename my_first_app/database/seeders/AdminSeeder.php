<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = 'admin@pupsantarosa.edu.ph';
        $adminPass  = 'admin123';

        $usernameHash = hash('sha256', strtolower(trim($adminEmail)));
        $passwordHash = hash('sha256', $adminPass);

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'          => 'System Administrator',
                'username_hash' => $usernameHash,
                'password'      => $passwordHash,
                'role'          => 'admin',
                'is_approved'   => true,
            ]
        );

        // Also update any pre-existing user rows to admin and approved so nobody is locked out
        User::query()->where('role', 'teacher')->whereNull('is_approved')->update([
            'role'        => 'admin',
            'is_approved' => true,
        ]);
    }
}
