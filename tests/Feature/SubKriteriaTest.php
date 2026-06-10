<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubKriteriaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_superadmin_can_access_sub_kriteria_index_and_see_grouped_layout(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        $kriteria = Kriteria::create([
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Biaya Simaksi',
            'bobot' => 0.2,
            'tipe' => 'Cost',
            'user_id' => $user->id
        ]);

        $subKriteria = SubKriteria::create([
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Sangat Murah',
            'bobot' => 5
        ]);

        $response = $this->actingAs($user)->get('/admin/sub-kriteria');
        $response->assertStatus(200);
        $response->assertViewHas('kriterias');
        $response->assertSee('Biaya Simaksi');
        $response->assertSee('Sangat Murah');
        $response->assertSee('5');
    }

    public function test_superadmin_can_create_sub_kriteria(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        $kriteria = Kriteria::create([
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Biaya Simaksi',
            'bobot' => 0.2,
            'tipe' => 'Cost',
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->post('/admin/sub-kriteria', [
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Murah',
            'bobot' => 4
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sub-Kriteria berhasil ditambahkan!');

        $this->assertDatabaseHas('sub_kriterias', [
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Murah',
            'bobot' => 4
        ]);
    }

    public function test_superadmin_can_update_sub_kriteria(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        $kriteria = Kriteria::create([
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Biaya Simaksi',
            'bobot' => 0.2,
            'tipe' => 'Cost',
            'user_id' => $user->id
        ]);

        $sub = SubKriteria::create([
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Sangat Murah',
            'bobot' => 5
        ]);

        $response = $this->actingAs($user)->put("/admin/sub-kriteria/{$sub->id}", [
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Sangat Murah Sekali',
            'bobot' => 5
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sub-Kriteria berhasil diperbarui!');

        $this->assertDatabaseHas('sub_kriterias', [
            'id' => $sub->id,
            'nama_sub' => 'Sangat Murah Sekali',
            'bobot' => 5
        ]);
    }

    public function test_superadmin_can_delete_sub_kriteria(): void
    {
        $superadminRole = Role::where('name', 'Superadmin')->first();
        $user = User::factory()->create([
            'role_id' => $superadminRole->id,
        ]);

        $kriteria = Kriteria::create([
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'Biaya Simaksi',
            'bobot' => 0.2,
            'tipe' => 'Cost',
            'user_id' => $user->id
        ]);

        $sub = SubKriteria::create([
            'kriteria_id' => $kriteria->id,
            'nama_sub' => 'Sangat Murah',
            'bobot' => 5
        ]);

        $response = $this->actingAs($user)->delete("/admin/sub-kriteria/{$sub->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sub-Kriteria berhasil dihapus!');

        $this->assertDatabaseMissing('sub_kriterias', [
            'id' => $sub->id
        ]);
    }
}
