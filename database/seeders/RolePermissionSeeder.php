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

        // Clean up old permissions if they exist
        Permission::whereIn('name', ['manage_master_data', 'manage_moora'])->delete();

        // 2. Create Permissions
        $permissions = [
            'manage_users' => 'Kelola Akun Admin',
            'view_logs' => 'Melihat Log Aktivitas',
            'manage_gunung' => 'Mengelola Data Gunung',
            'manage_terminal' => 'Mengelola Data Terminal',
            'manage_jalur' => 'Mengelola Data Jalur',
            'manage_biaya' => 'Mengelola Data Biaya & Armada',
            'manage_kriteria' => 'Mengelola Data Kriteria',
            'manage_sub_kriteria' => 'Mengelola Data Sub-Kriteria',
            'manage_penilaian' => 'Mengelola Penilaian Alternatif',
            'view_hasil' => 'Melihat Hasil Perangkingan MOORA',
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

        // Admin gets master data and moora menus individually
        $admin->permissions()->sync([
            $permissionModels['manage_gunung']->id,
            $permissionModels['manage_terminal']->id,
            $permissionModels['manage_jalur']->id,
            $permissionModels['manage_biaya']->id,
            $permissionModels['manage_kriteria']->id,
            $permissionModels['manage_sub_kriteria']->id,
            $permissionModels['manage_penilaian']->id,
            $permissionModels['view_hasil']->id,
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
