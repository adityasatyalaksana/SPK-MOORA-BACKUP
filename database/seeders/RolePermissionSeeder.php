<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);

        // 2. Create Permissions
        $permissions = [
            'manage_users' => 'Kelola Akun Admin',
            'view_logs' => 'Melihat Log Aktivitas',
            'manage_master_data' => 'Mengelola Data Gunung, Terminal, Jalur, Biaya',
            'manage_moora' => 'Mengelola Kriteria, Penilaian & Hasil',
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $label) {
            $permissionModels[$name] = Permission::firstOrCreate(
                ['name' => $name],
                ['label' => $label]
            );
        }

        // 3. Map Permissions to Roles
        // Superadmin gets everything
        $superadmin->permissions()->sync(array_column($permissionModels, 'id'));

        // Admin gets master data and moora
        $admin->permissions()->sync([
            $permissionModels['manage_master_data']->id,
            $permissionModels['manage_moora']->id,
        ]);

        // 4. Update Existing Users to roles
        // Aditya (id 1) should be Superadmin
        $userAditya = User::find(1);
        if ($userAditya) {
            $userAditya->update(['role_id' => $superadmin->id]);
        }

        // nanang (id 5) should be Admin
        $userNanang = User::find(5);
        if ($userNanang) {
            $userNanang->update(['role_id' => $admin->id]);
        }
        
        // For other users, default to Admin
        User::whereNull('role_id')->update(['role_id' => $admin->id]);
    }
}
