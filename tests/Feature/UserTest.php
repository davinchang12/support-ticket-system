<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $admin, $agent, $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->admin = User::factory()->create()->assignRole('admin');
    }

    public function test_admin_can_open_user_page_without_data_except_admin_account()
    {
        $response = $this->actingAs($this->admin)->get('/home/users');
        $response->assertStatus(200);

        $response->assertSee('No user found.');
    }

    public  function test_admin_can_open_user_page_with_data_except_admin_account()
    {
        $user = User::factory()->create()->assignRole('customer');

        $response = $this->actingAs($this->admin)->get('/home/users');
        $response->assertStatus(200);

        $response->assertDontSee('No user found.');
        $response->assertViewHas('users', function ($users) use ($user) {
            return $users->contains($user);
        });
    }

    public function test_admin_cannot_create_new_user()
    {
        $response = $this->actingAs($this->admin)->get('/home/users/create');

        $response->assertStatus(405);

        $response = $this->actingAs($this->admin)->post('/home/users', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(405);
    }

    public function test_admin_can_edit_and_update_user_role()
    {
        $user = User::factory()->create()->assignRole('customer');

        $response = $this->actingAs($this->admin)->get('/home/users/' . $user->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit User');

        $response = $this->actingAs($this->admin)->put('/home/users/' . $user->id, [
            'role' => 'agent',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/users');

        $user = User::find($user->id);
        $this->assertEquals('agent', $user->getRoleNames()->toArray()[0]);
    }

    public function test_admin_cannot_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/users/' . $user->id);
        $response->assertStatus(405);
    }

    public function test_agent_and_customer_cannot_access_any_user_page_and_crud()
    {
        $agent = User::factory()->create()->assignRole('agent');
        $customer = User::factory()->create()->assignRole('customer');

        $user = User::factory()->create();

        // Agent
        $response = $this->actingAs($agent)->get('/home/users');
        $response->assertStatus(403);

        $response = $this->actingAs($agent)->get('/home/users/create');
        $response->assertStatus(405);

        $response = $this->actingAs($agent)->post('/home/users', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(405);

        $response = $this->actingAs($agent)->get('/home/users/' . $user->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($agent)->put('/home/users/' . $user->id, [
            'role' => 'agent',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($agent)->delete('/home/users/' . $user->id);
        $response->assertStatus(405);

        // Customer
        $response = $this->actingAs($customer)->get('/home/users');
        $response->assertStatus(403);

        $response = $this->actingAs($customer)->get('/home/users/create');
        $response->assertStatus(405);

        $response = $this->actingAs($customer)->post('/home/users', [
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(405);

        $response = $this->actingAs($customer)->get('/home/users/' . $user->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($customer)->put('/home/users/' . $user->id, [
            'role' => 'agent',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($customer)->delete('/home/users/' . $user->id);
        $response->assertStatus(405);
    }
}
