<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_guest_cannot_access_homepage_and_redirect_to_login()
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_guest_can_register_and_has_customer_role()
    {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home');

        $user = User::first();
        $this->assertEquals('customer', $user->getRoleNames()->toArray()[0]);
    }

    public function test_customer_and_agent_can_login_and_redirect_to_ticket_page()
    {
        $user = User::factory()->create()->assignRole('customer');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/tickets');
    }

    public function test_admin_can_login_and_redirect_to_dashboard()
    {
        $user = User::factory()->create()->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home');
    }
}
