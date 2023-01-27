<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private $admin, $agent, $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->admin = User::factory()->create()->assignRole('admin');
        $this->agent = User::factory()->create()->assignRole('agent');
        $this->customer = User::factory()->create()->assignRole('customer');
    }
    
    public function test_admin_can_open_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/home');

        $response->assertStatus(200);
        $response->assertSee('Total tickets');
        $response->assertSee('Total open tickets');
        $response->assertSee('Total in progress tickets');
        $response->assertSee('Total cancelled tickets');
        $response->assertSee('Total completed tickets');
    }

    public function test_agent_and_customer_cannot_open_dashboard() {
        $response = $this->actingAs($this->agent)->get('/home');

        $response->assertStatus(403);
        
        $response = $this->actingAs($this->customer)->get('/home');

        $response->assertStatus(403);
    }
}
