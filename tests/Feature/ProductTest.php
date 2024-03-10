<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;


class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_auth_user_can_read_all_products()
    {
        $this->actingAs(User::factory()->create());

        Product::factory()->count(8)->create();

        $this->get('/api/products');

        $this->assertDatabaseCount('products', 8);//3 this will pass

    }

    public function test_an_auth_user_can_read_a_product()
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->get('/api/products/'.$product->id);

        $response->assertSuccessful();
        $response->assertJson([
               'data' => [
                   'id'          => $product->id,
                   'name'        => $product->name,
                   'description' => $product->description,
                   'price'       => $product->price,
                   'category_id' => $product->category_id,
               ]
           ]);

    }
    
}
