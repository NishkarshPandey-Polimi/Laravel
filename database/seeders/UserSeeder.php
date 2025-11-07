<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Make sure roles exist first
        $roles = ['admin', 'editor', 'user'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 1️⃣ Create 1 admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // 2️⃣ Create 2 editors
        $editors = User::factory(2)->create();
        foreach ($editors as $editor) {
            $editor->assignRole('editor');
        }

        // 3️⃣ Create 2 normal users
        $users = User::factory(2)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }

        // Optional: show a console message
        $this->command->info('✅ 5 fake users created: 1 admin, 2 editors, 2 users');
    }
}
