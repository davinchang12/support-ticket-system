<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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

    public function test_admin_can_open_category_page_without_data()
    {
        $response = $this->actingAs($this->admin)->get('/home/categories');
        $response->assertStatus(200);

        $response->assertSee('No category found.');
    }

    public  function test_admin_can_open_category_page_with_data()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/categories');
        $response->assertStatus(200);

        $response->assertDontSee('No category found.');
        $response->assertViewHas('categories', function ($categories) use ($category) {
            return $categories->contains($category);
        });
    }

    public function test_admin_can_create_new_category()
    {
        $response = $this->actingAs($this->admin)->get('/home/categories/create');

        $response->assertStatus(200);
        $response->assertSee('Create new Category');

        $response = $this->actingAs($this->admin)->post('/home/categories', [
            'name' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test',
        ]);
    }

    public function test_admin_can_edit_and_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->get('/home/categories/' . $category->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee('Edit Category');

        $response = $this->actingAs($this->admin)->put('/home/categories/' . $category->id, [
            'name' => 'Test',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/home/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test',
        ]);
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete('/home/categories/' . $category->id);

        $response->assertStatus(302);
        $response->assertRedirect('/home/categories');

        $this->assertDatabaseMissing('categories', [
            'name' => $category->name,
        ]);
    }

    public function test_agent_and_customer_cannot_access_any_category_page_and_crud()
    {
        $category = Category::factory()->create();

        // Agent
        $response = $this->actingAs($this->agent)->get('/home/categories');
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->agent)->get('/home/categories/create');
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->agent)->post('/home/categories', [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->agent)->get('/home/categories/' . $category->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($this->agent)->put('/home/categories/' . $category->id, [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->agent)->delete('/home/categories/' . $category->id);
        $response->assertStatus(403);
        
        // Customer
        $response = $this->actingAs($this->customer)->get('/home/categories');
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->customer)->get('/home/categories/create');
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->customer)->post('/home/categories', [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->customer)->get('/home/categories/' . $category->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($this->customer)->put('/home/categories/' . $category->id, [
            'name' => 'Test',
        ]);
        $response->assertStatus(403);
        
        $response = $this->actingAs($this->customer)->delete('/home/categories/' . $category->id);
        $response->assertStatus(403);
    }
}
