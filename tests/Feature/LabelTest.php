<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Label;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends TestCase
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

    public function test_admin_can_open_label_page_without_data()
    {
        $response = $this->actingAs($this->admin)->get('/home/labels');
        $response->assertStatus(200);

        $response->assertSee('No label found.');
    }

    public  function test_admin_can_open_label_page_with_data()
    {
        $label = Label::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/labels');
        $response->assertStatus(200);

        $response->assertDontSee('No label found.');
        $response->assertViewHas('labels', function ($labels) use ($label) {
            return $labels->contains($label);
        });
    }

    public function test_admin_can_create_new_label()
    {
        $response = $this->actingAs($this->admin)->get('/home/labels/create');

        $response->assertStatus(200);
        $response->assertSee('Create new Label');

        $response = $this->actingAs($this->admin)->post('/home/labels', [
            'name' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'Test',
        ]);
    }

    public function test_admin_can_edit_and_update_label()
    {
        $label = Label::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/labels/' . $label->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Label');

        $response = $this->actingAs($this->admin)->put('/home/labels/' . $label->id, [
            'name' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/labels');

        $this->assertDatabaseHas('labels', [
            'name' => 'Test',
        ]);
    }

    public function test_admin_can_delete_label()
    {
        $label = Label::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/labels/' . $label->id);

        $response->assertStatus(302);
        $response->assertRedirect('/home/labels');

        $this->assertDatabaseMissing('labels', [
            'name' => $label->name,
        ]);
    }

    public function test_agent_and_customer_cannot_access_any_label_page_and_crud()
    {
        $label = Label::factory()->create();

        // Agent
        $response = $this->actingAs($this->agent)->get('/home/labels');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->get('/home/labels/create');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->post('/home/labels', [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->get('/home/labels/' . $label->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->put('/home/labels/' . $label->id, [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->delete('/home/labels/' . $label->id);
        $response->assertStatus(403);

        // Customer
        $response = $this->actingAs($this->customer)->get('/home/labels');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->get('/home/labels/create');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->post('/home/labels', [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->get('/home/labels/' . $label->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->put('/home/labels/' . $label->id, [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->delete('/home/labels/' . $label->id);
        $response->assertStatus(403);
    }
}
