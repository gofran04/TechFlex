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

    public function test_an_auth_user_can_create_a_product()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $data = Product::factory()->make(['name' => 'product 1']);

        $this->post('/api/products', $data->toArray());

        $this->assertEquals(1,Product::all()->count());
        $this->assertDatabaseHas('products',$data->toArray());
    }

    public function test_an_auth_user_can_update_a_product()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create(['name' => 'product 1']);

        $product->name = 'new name';
        $product->price = 20;


        $this->patch('/api/products/'.$product->id, $product->toArray());

        $this->assertDatabaseHas('products',[
            'name' => 'new name',
        ]);
    }

    public function test_an_auth_user_can_delete_a_product()
    {
        $this->actingAs(User::factory()->create());

        $product = Product::factory()->create();

        $response = $this->delete('/api/products/'.$product->id);

        $this->assertEquals(0,Product::all()->count());
        $this->assertSoftDeleted($product);
    }
    
}
