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
    use RefreshDatabase;
    protected User $user;
    protected User $admin;


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

    public function test_create_product_successful()
    {

        $product = [
            'name' => 'Hoodie No 1',
            'price' => 121,
        ];
        $response = $this->actingAs($this->admin)->post('/products', $product);

        // dd($response->status());

        // $response->assertStatus(302);
        $response->assertFound();
        $response->assertRedirect('/products');

        $this->assertDatabaseHas('products', $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($lastProduct->name, $product['name']);
        $this->assertEquals($lastProduct->price, $product['price']);
    }

    public function test_edit_contains_correct_values()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->get("/products/{$product->id}/edit");

        $response->assertOk();
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
    }

    public function test_update_product_failed_and_redirect_back_to_edit_form()
    {

        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'name' => '',
            'price' => '',
        ]);

        $response->assertFound();
        // $response->assertSessionHasErrors(['name', 'price']);
        $response->assertInvalid(['name', 'price']);
    }

    public function test_update_product_successful_and_redirect_to_product_index()
    {
        $productDataForUpdate = [
            'name' => 'Nangka',
            'price' => 120,
        ];

        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", $productDataForUpdate);

        $response->assertFound();
        $response->assertRedirectToRoute('products.index');

        $lastProduct = Product::latest()->first();
        $this->assertEquals($lastProduct->name, $productDataForUpdate['name']);
        $this->assertEquals($lastProduct->price, $productDataForUpdate['price']);
    }

    public function test_delete_product_successful()
    {

        $product = Product::factory()->create();
        // * With many kind assertion
        $this->assertDatabaseCount('products', 1);
        // $productCount = Product::count();
        // $this->assertEquals(1, $productCount);
        // $this->assertGreaterThanOrEqual(0, $productCount);
        
        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");

        $response->assertFound();
        $response->assertRedirect('/products');

        // * With many kind assertion
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseMissing('products', $product->toArray());
        // $productCount = Product::count();
        // $this->assertLessThanOrEqual(0, $productCount);
        // $this->assertEquals(0, $productCount);

    }

    private function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create(['is_admin' => $isAdmin]);
    }
}
