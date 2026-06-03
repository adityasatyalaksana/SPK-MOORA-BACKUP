<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_superadmin_can_access_all_admin_areas(): void
    {
        // 1. Create a Superadmin user
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        // 2. Act as Superadmin
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/admin/logs');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/admin/gunung');
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_user_management_or_logs(): void
    {
        // 1. Create an Admin user
        $adminRole = Role::where('name', 'Admin')->first();
        $user = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        // 2. Act as Admin, should get 403 on user management and logs
        $response = $this->actingAs($user)->get('/admin/users');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/logs');
        $response->assertStatus(403);

        // 3. Admin should still be able to access master data
        $response = $this->actingAs($user)->get('/admin/gunung');
        $response->assertStatus(200);
    }

    public function test_superadmin_can_change_admin_role_permissions_dynamically(): void
    {
        // 1. Get Roles and permissions
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        
        $superadmin = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);
        
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        // 2. Initially Admin has 'manage_moora' access (which maps to /admin/hasil)
        $response = $this->actingAs($admin)->get('/admin/hasil');
        $response->assertStatus(200);

        // 3. Superadmin disables 'manage_moora' permission for Admin role
        // 'manage_master_data' permission ID
        $masterDataPerm = Permission::where('name', 'manage_master_data')->first();
        
        // Post updated permissions (only keep manage_master_data for Admin)
        $response = $this->actingAs($superadmin)->post('/admin/users/permissions', [
            'permissions' => [
                $superadminRole->id => Permission::all()->pluck('id')->toArray(),
                $adminRole->id => [$masterDataPerm->id]
            ]
        ]);
        $response->assertRedirect('/admin/users');

        // 4. Admin tries to access /admin/hasil again, should get 403
        $response = $this->actingAs($admin->fresh())->get('/admin/hasil');
        $response->assertStatus(403);

        // 5. Admin can still access /admin/gunung (manage_master_data)
        $response = $this->actingAs($admin->fresh())->get('/admin/gunung');
        $response->assertStatus(200);
    }
}
