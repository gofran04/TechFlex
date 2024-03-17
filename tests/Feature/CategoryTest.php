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

    }

