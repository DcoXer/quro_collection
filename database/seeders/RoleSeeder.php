<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Buat admin user kalau belum ada
        $admin = User::firstOrCreate(
            ['email' => config('admin.email', 'admin@qurocollection.com')],
            [
                'name'     => 'Admin',
                'password' => Hash::make(config('admin.password', 'password')),
            ]
        );

        $admin->assignRole('admin');

        // Assign user role ke semua user lain
        User::where('email', '!=', config('admin.email', 'admin@qurocollection.com'))
            ->each(function ($user) {
                if (!$user->hasAnyRole(['admin', 'user'])) {
                    $user->assignRole('user');
                }
            });
    }
}