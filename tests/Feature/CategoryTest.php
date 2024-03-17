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

    }

