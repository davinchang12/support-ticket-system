<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\User;
use Tests\TestCase;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
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

    // Admin Section
    public function test_admin_can_open_ticket_page_without_data()
    {
        $response = $this->actingAs($this->admin)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertSee('No ticket found.');
    }

    public function test_admin_can_open_ticket_page_with_data()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertDontSee('No ticket found.');
        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->contains($ticket);
        });
    }

    public function test_admin_cannot_create_ticket()
    {
        $response = $this->actingAs($this->admin)->get('/home/tickets');
        $response->assertDontSee('Create ticket');

        $response = $this->actingAs($this->admin)->get('/home/tickets/create');
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->post('/home/tickets', [
            'title' => '',
            'description' => '',
            'priority' => '',
            'status' => '',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_edit_ticket_and_assign_agent()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/tickets/' . $ticket->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Ticket');
        $response->assertSee('Assign Agent');

        $response = $this->actingAs($this->admin)->put('/home/tickets/' . $ticket->id, [
            'title' => $ticket->title,
            'description' => $ticket->description,
            'categories' => [$category->id],
            'labels' => [$label->id],
            'priority' => 'high',
            'agent_id' => $this->agent->id,
            'status' => 'completed',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets');

        $this->assertDatabaseHas('tickets', [
            'agent_id' => $this->agent->id,
            'status' => 'completed',
        ]);
    }

    // Agent Section
    public function test_agent_can_open_ticket_page_without_data()
    {
        $response = $this->actingAs($this->agent)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertSee('No ticket found.');
    }

    public function test_agent_can_open_ticket_page_with_data()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->agent)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertSee('No ticket found.');

        $ticket->update([
            'agent_id' => $this->agent->id,
        ]);

        $response = $this->actingAs($this->agent)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertDontSee('No ticket found.');

        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->contains($ticket);
        });
    }

    public function test_agent_cannot_create_ticket()
    {
        $response = $this->actingAs($this->agent)->get('/home/tickets');
        $response->assertDontSee('Create ticket');

        $response = $this->actingAs($this->agent)->get('/home/tickets/create');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->post('/home/tickets', [
            'title' => '',
            'description' => '',
            'priority' => '',
            'status' => '',
        ]);

        $response->assertStatus(403);
    }

    public function test_agent_can_edit_ticket_but_cannot_assign_agent()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->agent)->get('/home/tickets/' . $ticket->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Ticket');
        $response->assertDontSee('Assign Agent');

        $response = $this->actingAs($this->agent)->put('/home/tickets/' . $ticket->id, [
            'title' => $ticket->title,
            'description' => $ticket->description,
            'categories' => [$category->id],
            'labels' => [$label->id],
            'priority' => 'high',
            'status' => 'completed',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets');

        $this->assertDatabaseHas('tickets', [
            'status' => 'completed',
        ]);
    }

    // Customer Section
    public function test_customer_can_open_ticket_page_without_data()
    {
        $response = $this->actingAs($this->customer)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertSee('No ticket found.');
    }

    public function test_customer_can_open_ticket_page_with_data()
    {
        $ticket = Ticket::factory()->create([
            'customer_id' => $this->customer->id,
        ]);

        $response = $this->actingAs($this->customer)->get('/home/tickets');

        $response->assertStatus(200);
        $response->assertDontSee('No ticket found.');

        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->contains($ticket);
        });
    }

    public function test_customer_can_create_ticket()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();

        $response = $this->actingAs($this->customer)->get('/home/tickets');
        $response->assertSee('Create ticket');

        $response = $this->actingAs($this->customer)->get('/home/tickets/create');
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer)->post('/home/tickets', [
            'title' => 'Test',
            'description' => 'test',
            'categories' => [$category->id],
            'labels' => [$label->id],
            'priority' => 'high',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('tickets', [
            'title' => 'Test',
        ]);
    }

    public function test_customer_cannot_edit_ticket()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->customer)->get('/home/tickets/' . $ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->put('/home/tickets/' . $ticket->id, [
            'title' => $ticket->title,
            'description' => $ticket->description,
            'categories' => [$category->id],
            'labels' => [$label->id],
            'priority' => 'high',
            'status' => 'completed',
        ]);

        $response->assertStatus(403);
    }
}
