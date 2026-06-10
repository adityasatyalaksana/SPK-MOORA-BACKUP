<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\ActivityLog;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_logs_index_page_uses_pagination(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        // Create 20 activity logs
        for ($i = 0; $i < 20; $i++) {
            ActivityLog::create([
                'user_id' => $user->id,
                'activity' => "Log ke-{$i}"
            ]);
        }

        $response = $this->actingAs($user)->get('/admin/logs');
        $response->assertStatus(200);
        // It should display 15 logs per page
        $response->assertViewHas('logs');
        $logs = $response->viewData('logs');
        $this->assertEquals(15, $logs->count());
        $this->assertEquals(20, $logs->total());
    }

    public function test_user_without_view_logs_permission_cannot_export_or_clear_logs(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        // Admin role does NOT have view_logs permission
        $user = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($user)->get('/admin/logs/export');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->post('/admin/logs/clear', ['clear_type' => 'all']);
        $response->assertStatus(403);
    }

    public function test_superadmin_can_export_logs_to_csv(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => "Uji coba ekspor data log"
        ]);

        $response = $this->actingAs($user)->get('/admin/logs/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=' . 'activity_logs_' . date('Ymd_His') . '.csv');
        
        // Check output stream contains data
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('Uji coba ekspor data log', $output);
        $this->assertStringContainsString('Waktu & Tanggal', $output);
    }

    public function test_superadmin_can_clear_all_logs(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'activity' => "Log untuk dihapus"
        ]);

        $this->assertEquals(1, ActivityLog::where('activity', "Log untuk dihapus")->count());

        $response = $this->actingAs($user)->post('/admin/logs/clear', [
            'clear_type' => 'all'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // All logs cleared, but a new log entry is created indicating the clear action
        $this->assertEquals(0, ActivityLog::where('activity', "Log untuk dihapus")->count());
        $this->assertEquals(1, ActivityLog::count());
        $this->assertEquals("Membersihkan seluruh riwayat log aktivitas sistem", ActivityLog::first()->activity);
    }

    public function test_superadmin_can_clear_logs_older_than_30_days(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        // Create a log from 40 days ago
        $oldLog = ActivityLog::create([
            'user_id' => $user->id,
            'activity' => "Log sangat lama"
        ]);
        $oldLog->created_at = now()->subDays(40);
        $oldLog->save();

        // Create a recent log
        $newLog = ActivityLog::create([
            'user_id' => $user->id,
            'activity' => "Log baru"
        ]);

        $this->assertEquals(2, ActivityLog::count());

        $response = $this->actingAs($user)->post('/admin/logs/clear', [
            'clear_type' => 'older_than_30_days'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Old log should be deleted, new log stays, and 1 clear action log is created
        $this->assertEquals(0, ActivityLog::where('activity', "Log sangat lama")->count());
        $this->assertEquals(1, ActivityLog::where('activity', "Log baru")->count());
        $this->assertEquals(2, ActivityLog::count()); // "Log baru" + "Menghapus log aktivitas yang berusia lebih dari 30 hari..."
    }
}
