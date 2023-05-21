<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected User $user;
    protected User $admin;

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
        // $this->user = User::whereName('rudi')->first();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_product_contains_empty_tables()
    {
        // $user = User::factory()->create();
        $user = User::whereName('rudi')->first();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertSeeText('no products found');
        // $this->assertTrue($user->delete());
    }

    public function test_product_contains_non_empty_tables()
    {
        $product = Product::create([
            'name' => "Salamander Merch",
            'price' => 150,
        ]);

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Salamander Merch');

        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginated_product_doesnt_contain_11th_record_data()
    {
        $lengthData = 11;
        $products = Product::factory($lengthData)->create();
        $lastProduct = $products->last();
        // dd($products);
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $this->assertCount($lengthData, $products);
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return $collection->contains($lastProduct);
        });
    }

    public function test_non_admin_cannot_see_product_create_button()
    {
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);
        $response->assertDontSee('Add new product');
    }

    public function test_admin_can_see_product_create_button()
    {
        $response = $this->actingAs($this->admin)->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Add new product');
    }

    public function test_non_admin_cannot_access_product_create_page()
    {
        $response = $this->actingAs($this->user)->get('/products/create');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_product_create_page()
    {

        $response = $this->actingAs($this->admin)->get('/products/create');

        $response->assertStatus(200);
    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create(['is_admin' => $isAdmin]);
    }
}
