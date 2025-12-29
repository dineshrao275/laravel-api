<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'admin role'
        ]);

        $user = User::create([
            'name' => 'Dinesh Admin',
            'email' => 'dineshrao275@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user->roles()->attach($role->id);

        $permission = Permission::create([
            'name' => 'all',
            'slug' => 'all',
            'description' => 'all permissions',
        ]);

        $role->permissions()->attach($permission->id);
    }
}
