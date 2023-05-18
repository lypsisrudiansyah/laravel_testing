<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected $user;
    
    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::whereName('rudi')->first();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_product_contain_empty_tables()
    {
        // $user = User::factory()->create();
        $user = User::whereName('rudi')->first();

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertSeeText('no products found');
        // $this->assertTrue($user->delete());
    }

    public function test_product_contain_non_empty_tables()
    {
        Product::create([
            'name' => "Salamander Merch",
            'price' => 15000,
        ]);

        $response = $this->actingAs($this->user)->get('/products');

        $response->assertStatus(200);

        $response->assertSeeText('no products found');
        // $this->assertTrue($user->delete());
    }
}
