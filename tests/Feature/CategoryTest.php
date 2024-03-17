<?php

namespace Tests\Feature;

use Carbon\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Session\TokenMismatchException;

class CategoryTest extends TestCase
{
    use RefreshDatabase;


    public function test_all_users_can_read_all_categories()
    {
        Category::factory()->count(8)->create();

        $this->get('/api/categories');

        $this->assertDatabaseCount('categories', 8);
    }

    public function test_all_users_can_read_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories/'.$category->id);

        $response->assertSuccessful();
        $response->assertJson([
               'data' => [
                   'id'          => $category->id,
                   'name'        => $category->name,
               ]
           ]);
    }

    public function test_an_auth_user_can_create_a_category()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $data = Category::factory()->make(['name' => 'category 1']);

        $this->post('/api/categories', $data->toArray());

        $this->assertEquals(1,Category::all()->count());
        $this->assertDatabaseHas('categories',$data->toArray());
    }

    public function test_an_auth_user_can_update_a_category()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create(['name' => 'category 1']);

        $category->name = 'new name';
        $category->price = 20;


        $this->patch('/api/categories/'.$category->id, $category->toArray());

        $this->assertDatabaseHas('categories',[
            'name' => 'new name',
        ]);
    }

    public function test_an_auth_user_can_delete_a_category()
    {
        $this->actingAs(User::factory()->create());

        $category = Category::factory()->create();

        $this->delete('/api/categories/'.$category->id);

        $this->assertEquals(0,Category::all()->count());
        $this->assertSoftDeleted($category);
    }

    }

