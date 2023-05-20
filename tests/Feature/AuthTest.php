<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    
    protected $user;
    protected $password = '1234';

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'rudi',
            'email' => 'rudio@mail.com',
            'password' => bcrypt($this->password)
        ]);
        // $this->user = User::whereName('rudi')->first();
    }
    
    public function test_login_redirect_to_products()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('products');
    }

    public function test_user_unauthenticated_cannot_access_product()
    {
        $response = $this->get('/products');
        
        // This is the expected behavior of Laravel validation if the validation does not pass
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_user_authenticated_can_access_product()
    {
        $response = $this->actingAs($this->user)->get('/products');
        
        $response->assertStatus(200);
    }
}
