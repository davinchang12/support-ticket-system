<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\Category;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
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

    public function test_admin_can_make_comment()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');

        $response = $this->actingAs($this->admin)->post('/home/tickets/comments', [
            'user_id' => $this->admin->id,
            'ticket_id' => $ticket->id,
            'comment' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets/' . $ticket->id);

        $response = $this->actingAs($this->admin)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');
        $response->assertSee('Test');
        
        $this->assertDatabaseHas('comments', [
            'comment' => 'Test',
        ]);
    }

    public function test_agent_can_make_comment()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->agent)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');

        $response = $this->actingAs($this->agent)->post('/home/tickets/comments', [
            'user_id' => $this->agent->id,
            'ticket_id' => $ticket->id,
            'comment' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets/' . $ticket->id);

        $response = $this->actingAs($this->agent)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');
        $response->assertSee('Test');
        
        $this->assertDatabaseHas('comments', [
            'comment' => 'Test',
        ]);
    }

    public function test_customer_can_make_comment()
    {
        $category = Category::factory()->create();
        $label = Label::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($this->customer)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');

        $response = $this->actingAs($this->customer)->post('/home/tickets/comments', [
            'user_id' => $this->customer->id,
            'ticket_id' => $ticket->id,
            'comment' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets/' . $ticket->id);

        $response = $this->actingAs($this->customer)->get('/home/tickets/' . $ticket->id);

        $response->assertStatus(200);
        $response->assertSee('Comments');
        $response->assertSee('Test');
        
        $this->assertDatabaseHas('comments', [
            'comment' => 'Test',
        ]);
    }
}
