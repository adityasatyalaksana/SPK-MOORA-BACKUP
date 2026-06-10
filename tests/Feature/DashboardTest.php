<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Gunung;
use App\Models\Jalur;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_authorized_users_see_unified_dashboard_widgets(): void
    {
        foreach (['Superadmin', 'Admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            $user = User::factory()->create([
                'role_id' => $role->id,
            ]);

            $response = $this->actingAs($user)->get('/admin/dashboard');

            $response->assertStatus(200);
            $response->assertViewHas('data');
            $response->assertViewHas('recentLogs');
            $response->assertViewHas('topAlternatives');

            // Verify all 8 widgets and sections are visible for both roles
            $response->assertSee('Total Pengguna');
            $response->assertSee('Log Aktivitas');
            $response->assertSee('Terminal Transit');
            $response->assertSee('Armada &amp; Tarif', false);
            $response->assertSee('Penilaian Terisi');
        }
    }
}
