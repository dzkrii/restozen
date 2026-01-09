<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists to avoid duplication
        if (User::where('email', 'admin@marupos.com')->exists()) {
            $this->command->info('Admin user already exists.');
            return;
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@marupos.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_active' => true,
            // company_id is nullable, so we don't need to set it for the super admin
        ]);
        
        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@marupos.com');
        $this->command->info('Password: password');
    }
}
