<?php

namespace Tests\Feature;

use Carbon\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Session\TokenMismatchException;
use Database\Seeders\GeneralManagerSeeder;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            GeneralManagerSeeder::class,
            PermissionsSeeder::class,
        ]);
    }


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

    public function test_an_authenticated_and_authorized_user_can_create_a_category()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = Category::factory()->make(['name' => 'category 1']);
        $category_attribute = Category::factory()->make(['name' => 'category one']);
        $data = array_merge($category_attribute->toArray(),['category_pic' => $file]);

        $response =  $this->post('/api/categories', $data);

        $this->assertEquals(1,Category::all()->count());
        $response->assertCreated();
        $response->assertJson([
            'data' => [
            'name' => $category_attribute->name,
        ]]);
    }

    public function test_an_authenticated_and_authorized_user_can_update_a_category()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $category = $this->createCategory();

        $category->name = 'new name';

       $data = array_merge($category->toArray(),['category_pic' => $file]);
       $this->patch('/api/categories/'.$category->id, $data);        

        $this->assertDatabaseHas('categories',[
            'name' => 'new name',
        ]);
    }

    public function test_an_authenticated_and_authorized__user_can_delete_a_category()
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create();
        $user->assignRole('supervisor');

        $category = $this->createCategory();

        $this->actingAs($user)->delete('/api/categories/'.$category->id);

        $this->assertEquals(0,Category::all()->count());
        $this->assertSoftDeleted($category);
    }

    public function test_guest_can_not_create_update_or_delete_categories()
    {
        Category::factory()->count(2)->create();
        $data = Category::factory()->make(['name' => 'category 1']);
        $category = Category::find(1);

        $this->patch('/api/categories/'.$category->id, $category->toArray())->assertRedirect(route('login'));
        $this->post('/api/categories', $data->toArray())->assertRedirect(route('login'));
        $this->delete('/api/categories/'.$category->id)->assertRedirect(route('login'));
    }

    protected function createCategory()
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $category = Category::factory()->create(['name' => 'category one']);
        $category->addMedia($file)->toMediaCollection('category_pic');

        return $category;
    }

}

