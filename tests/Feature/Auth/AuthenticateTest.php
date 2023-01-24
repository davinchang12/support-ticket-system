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

    public function test_guest_can_login()
    {
        $user = User::factory()->create()->assignRole('customer');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home');

        $response = $this->get('/home');
        $response->assertSee('logged in');
    }
}
