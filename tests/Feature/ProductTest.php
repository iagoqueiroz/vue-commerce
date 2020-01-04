<?php

namespace Tests\Feature;

use App\User;
use App\Product;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProductIndex()
    {
        $response = $this->json('GET', '/api/v1/products/catalog');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
    }

    public function testProductListUnauthorizeNormalUser()
    {
        $user = User::where('email', 'user@system.com')->first();

        $response = $this->actingAs($user, 'api')->json('GET', '/api/v1/products/list');
        $response->assertStatus(403);
        $response->assertJson([
            'error' => 'Unauthorized'
        ]);
    }

    public function testProductListAuthorizeAdminUser()
    {
        $user = User::where('email', 'admin@system.com')->first();

        $response = $this->actingAs($user, 'api')->json('GET', '/api/v1/products/list');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links',
            'meta'
        ]);
    }

    public function testCreateNewProduct()
    {
        $user = User::where('email', 'admin@system.com')->first();

        $image = UploadedFile::fake()->image('product.jpg', 620, 480);

        $response = $this->actingAs($user, 'api')->json('POST', '/api/v1/products/create', [
            'name' => 'Test Product',
            'price' => 500.30,
            'description' => 'The description of the test product',
            'category_id' => 1,
            'file' => $image
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id', 'name', 'price', 'description', 'category_id', 'image_path', 'created_at', 'updated_at'
            ]
        ]);
    }

    public function testDeleteProduct()
    {
        $user = User::where('email', 'admin@system.com')->first();

        $product = Product::where('name', 'Test Product')->first();

        $response = $this->actingAs($user, 'api')->json("DELETE", "/api/v1/products/delete/{$product->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertSoftDeleted('products', [
            'id' => $product->id
        ]);
    }
}
