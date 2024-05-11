<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\GeneralManagerSeeder;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        // $this->artisan("db:seed");
        $this->seed([
            GeneralManagerSeeder::class,
            PermissionsSeeder::class,
        ]);
    }

    public function test_all_users_can_read_all_products()
    {
        Product::factory()->count(8)->create();

        $this->get('/api/products');

        $this->assertDatabaseCount('products', 8);

    }

    public function test_all_users_can_read_a_product()
    {
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

    public function test_an_authenticated_and_authorized_user_can_create_a_product()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $product_attribute = Product::factory()->make(['name' => 'product one']);
        $data = array_merge($product_attribute->toArray(),['product_pic' => $file]);
        
        $response = $this->actingAs($user)->post('/api/products', $data);
        $this->assertEquals(1,Product::all()->count());
        $response->assertCreated();
        $response->assertJson([
            'data' => [
            'name' => $product_attribute->name,
        ]]);
    }

    public function test_an_authenticated_and_authorized_user_can_update_a_product()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');


        $product = $this->createProduct();
        $product->name = 'new name';

        $data = array_merge($product->toArray(),['product_pic' => $file]);
        $this->actingAs($user)->patch('/api/products/'.$product->id, $data);

        $this->assertDatabaseHas('products',[
            'name' => 'new name',
        ]);
    }

    public function test_an_authenticated_and_authorized_user_can_delete_a_product()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');

        $product = $this->createProduct();

        $this->actingAs($user)->delete('/api/products/'.$product->id);

        $this->assertEquals(0,Product::all()->count());
        $this->assertSoftDeleted($product);
    }

    public function test_guest_can_not_create_update_or_delete_products()
    {
        $data = Product::factory()->make(['name' => 'product 1']);
        $product = $this->createProduct();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->patch('/api/products/'.$product->id, array_merge($data->toArray(),['product_pic' => $file]))->assertRedirect(route('login'));
        $this->post('/api/products', array_merge($data->toArray(),['product_pic' => $file]))->assertRedirect(route('login'));
        $this->delete('/api/products/'.$product->id)->assertRedirect(route('login'));
    }

    public function test_unauthorized_users_can_not_create_update_or_delete_products()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $data = Product::factory()->make(['name' => 'product 1']);
        $product = $this->createProduct();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->patch('/api/products/'.$product->id, array_merge($product->toArray(),['product_pic' => $file]))->assertForbidden();
        $this->post('/api/products',array_merge($data->toArray(),['product_pic' => $file]))->assertForbidden();
        $this->delete('/api/products/'.$product->id)->assertForbidden();
    }

    public function test_user_can_not_show_update_delete_not_found_product()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');

        $data = Product::factory()->make(['name' => 'product 1']);
        $product = $this->createProduct();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');


        $this->get('/api/products/88')->assertNotFound();
        $this->actingAs($user)->patch('/api/products/88', array_merge($data->toArray(),['product_pic' => $file]))->assertNotFound();
        $this->actingAs($user)->delete('/api/products/88')->assertNotFound();
    }

    protected function createProduct()
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $product = Product::factory()->create(['name' => 'product one']);
        $product->addMedia($file)->toMediaCollection('product_pic');

        return $product;
    }
}
