<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();

        User::firstOrCreate(
            ['email' => 'jules@jjtms.com'],
            [
                'name' => 'Admin Jules',
                'password' => Hash::make('Jules123!'), // Use a secure password in production
                'role_id' => $adminRole->id,
            ]
        );
    }
}
