<?php

namespace Tests\Feature;

use App\Models\Rental;
use App\Models\User;
use App\Models\VM;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;
    protected VM $vm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
        $this->vm = VM::factory()->create();
    }

    public function test_index_requires_admin()
    {
        $this->actingAs($this->regularUser)
            ->get(route('rentals.index'))
            ->assertStatus(403);
    }

    public function test_index_admin_can_access()
    {
        Rental::factory(3)->create();

        $this->actingAs($this->adminUser)
            ->get(route('rentals.index'))
            ->assertStatus(200)
            ->assertSee('Rental');
    }

    public function test_create_requires_admin()
    {
        $this->actingAs($this->regularUser)
            ->get(route('rentals.create'))
            ->assertStatus(403);
    }

    public function test_store_requires_admin()
    {
        $this->actingAs($this->regularUser)
            ->post(route('rentals.store'), [
                'user_id' => $this->regularUser->id,
                'vm_id' => $this->vm->id,
                'start_date' => '01/01/2026',
                'end_date' => '10/01/2026',
                'status' => 'active',
                'admin_id' => $this->adminUser->id,
            ])
            ->assertStatus(403);
    }

    public function test_store_creates_rental_with_date_parsing()
    {
        $this->actingAs($this->adminUser)
            ->post(route('rentals.store'), [
                'user_id' => $this->regularUser->id,
                'vm_id' => $this->vm->id,
                'start_date' => '01/01/2026',
                'end_date' => '10/01/2026',
                'status' => 'active',
                'admin_id' => $this->adminUser->id,
            ])
            ->assertRedirect(route('rentals.index'));

        $rental = Rental::first();
        $this->assertEquals('2026-01-01', $rental->start_date->format('Y-m-d'));
        $this->assertEquals('2026-01-10', $rental->end_date->format('Y-m-d'));
    }

    public function test_store_rejects_invalid_date_format()
    {
        $this->actingAs($this->adminUser)
            ->post(route('rentals.store'), [
                'user_id' => $this->regularUser->id,
                'vm_id' => $this->vm->id,
                'start_date' => '2026-01-01', // Wrong format
                'end_date' => '2026-01-10',
                'status' => 'active',
                'admin_id' => $this->adminUser->id,
            ])
            ->assertSessionHasErrors('date');
    }

    public function test_store_rejects_end_date_before_start_date()
    {
        $this->actingAs($this->adminUser)
            ->post(route('rentals.store'), [
                'user_id' => $this->regularUser->id,
                'vm_id' => $this->vm->id,
                'start_date' => '10/01/2026',
                'end_date' => '01/01/2026', // Before start
                'status' => 'active',
                'admin_id' => $this->adminUser->id,
            ])
            ->assertSessionHasErrors('end_date');
    }

    public function test_update_requires_admin()
    {
        $rental = Rental::factory()->create();

        $this->actingAs($this->regularUser)
            ->put(route('rentals.update', $rental), [
                'user_id' => $this->regularUser->id,
                'vm_id' => $this->vm->id,
                'start_date' => '01/01/2026',
                'end_date' => '10/01/2026',
                'status' => 'active',
                'admin_id' => $this->adminUser->id,
            ])
            ->assertStatus(403);
    }

    public function test_delete_requires_admin()
    {
        $rental = Rental::factory()->create();

        $this->actingAs($this->regularUser)
            ->delete(route('rentals.destroy', $rental))
            ->assertStatus(403);
    }

    public function test_update_status_requires_admin()
    {
        $rental = Rental::factory()->create();

        $this->actingAs($this->regularUser)
            ->post(route('rentals.updateStatus', $rental), [
                'status' => 'expired',
            ])
            ->assertStatus(403);
    }

    public function test_admin_can_update_status()
    {
        $rental = Rental::factory()->create(['status' => 'active']);

        $this->actingAs($this->adminUser)
            ->post(route('rentals.updateStatus', $rental), [
                'status' => 'expired',
            ])
            ->assertStatus(200);

        $this->assertEquals('expired', $rental->fresh()->status);
    }
}
