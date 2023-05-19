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
    protected $user;

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
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
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();
        // dd($products);
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return $collection->contains($lastProduct);
        });
    }
}
