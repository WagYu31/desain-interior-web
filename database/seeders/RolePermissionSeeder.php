<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access admin dashboard',
            'manage projects',
            'manage orders',
            'manage categories',
            'manage team',
            'manage admins',
            'manage all orders',
            'view reports',
            'view own orders',
            'create orders',
            'cancel orders',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- 2. Buat Roles ---
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner']); 

        
        // Owner mendapatkan SEMUA permission
        $ownerRole->givePermissionTo(Permission::all());

        $adminPermissions = Permission::where('name', '!=', 'manage admins')->get();
        $adminRole->syncPermissions($adminPermissions);

        // User mendapatkan permission yang sangat spesifik
        $userRole->syncPermissions([
            'view own orders',
            'create orders',
            'cancel orders',
        ]);


        // Buat User Owner
        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@asthatunggalmakmur.ac.id'],
            [
                'name' => 'CEO',
                'password' => Hash::make('Password1!'),
            ]
        );
        $ownerUser->assignRole($ownerRole);

        // Buat User Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@asthatunggalmakmur.ac.id'],
            [
                'name' => 'ADMIN',
                'password' => Hash::make('Password1!'),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Buat User Biasa
        $regularUser = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'MAUL', 
                'password' => Hash::make('Password1!'),
            ]
        );
        $regularUser->assignRole($userRole);
    }
}