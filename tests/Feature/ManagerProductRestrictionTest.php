<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ManagerProductRestrictionTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_product_list()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get(route('manager.products.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Add New Product');
        $response->assertDontSee('Actions');
    }

    public function test_manager_cannot_create_product()
    {
        $manager = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($manager)
            ->get(route('manager.products.create'));

        $response->assertStatus(403);
    }

    public function test_manager_cannot_store_product()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $category = Category::factory()->create();

        $response = $this->actingAs($manager)
            ->post(route('manager.products.store'), [
                'name' => 'Should Fail',
                'category_id' => $category->id,
                'price' => 1000,
                'stock' => 10,
            ]);

        $response->assertStatus(403);
    }

    public function test_manager_cannot_edit_product()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($manager)
            ->get(route('manager.products.edit', $product));

        $response->assertStatus(403);
    }

    public function test_manager_cannot_update_product()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($manager)
            ->put(route('manager.products.update', $product), [
                'name' => 'Updated Name',
                'category_id' => $category->id,
                'price' => 2000,
                'stock' => 20,
            ]);

        $response->assertStatus(403);
    }

    public function test_manager_cannot_delete_product()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        $response = $this->actingAs($manager)
            ->delete(route('manager.products.destroy', $product));

        $response->assertStatus(403);
    }

    public function test_admin_can_still_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('manager.products.create'));

        $response->assertStatus(200);
    }
}
