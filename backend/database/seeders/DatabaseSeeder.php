<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@portoconnect.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@portoconnect.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create admin profile if not exists
        if ($adminUser->wasRecentlyCreated || !$adminUser->admin) {
            Admin::firstOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'user_id' => $adminUser->id,
                    'nama_lengkap' => 'Administrator',
                    'jabatan' => 'Super Admin',
                ]
            );
        }

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@portoconnect.com');
        $this->command->info('Password: admin123');
        $this->command->warn('⚠️  Please change the password after first login!');
    }
}
