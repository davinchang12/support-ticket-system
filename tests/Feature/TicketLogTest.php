<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketLogTest extends TestCase
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

    public function test_admin_can_open_ticket_logs_page_without_data()
    {
        $response = $this->actingAs($this->admin)->get('/home/ticketlogs');
        $response->assertStatus(200);

        $response->assertSee('No ticket log found.');
    }

    public  function test_admin_can_open_ticket_logs_page_with_data()
    {
        $ticketlogs = Ticket::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/ticketlogs');
        $response->assertStatus(200);

        $response->assertDontSee('No ticket log found.');
        $response->assertViewHas('tickets', function ($ticketlogss) use ($ticketlogs) {
            return $ticketlogss->contains($ticketlogs);
        });
    }

    public function test_admin_can_open_ticket_logs_detail()
    {
        $ticketlogs = Ticket::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/ticketlogs/' . $ticketlogs->id);

        $response->assertStatus(200);
    }

    public function test_agent_and_customer_cannot_access_any_ticket_logs_page()
    {
        $ticketlogs = Ticket::factory()->create();

        // Agent
        $response = $this->actingAs($this->agent)->get('/home/ticketlogs');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->get('/home/ticketlogs/' . $ticketlogs->id);
        $response->assertStatus(403);

        // Customer
        $response = $this->actingAs($this->customer)->get('/home/ticketlogs');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->get('/home/ticketlogs/' . $ticketlogs->id);
        $response->assertStatus(403);
    }
}
