<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\GeneralManagerSeeder;
use Database\Seeders\PermissionsSeeder;

class UserTest extends TestCase
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

    public function test_an_authenticated_and_authorized_user_can_read_all_users()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        User::factory()->count(8)->create();

        $response = $this->get('/api/users');
        $response->assertSuccessful();
        $this->assertDatabaseCount('users', count($response['data']));
    }

    public function test_an_authenticated_and_authorized_user_can_read_a_user()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        $user = User::factory()->create();
        $response = $this->get('/api/users/'.$user->id);

        $response->assertSuccessful();
        $this->assertDatabaseHas('users',$user->toArray());

    }

    public function test_an_authenticated_and_authorized_user_can_create_a_user()
    {

        $user = User::factory()->create();
        $user->assignRole('General-Manager');
        $this->actingAs($user);

        $user_attribute = [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'phone'                 => '0123456789',
            'address'               => 'user address',
            'type'                  => 'driver',

        ];
        $response = $this->post('/api/users', $user_attribute);
        $response->assertCreated();
        $response->assertJson([
            'data' => [
            'email' => $user_attribute['email'],
        ]]);
    }

    public function test_an_authenticated_and_authorized_user_can_update_a_user()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('supervisor');


        $user2 = User::factory()->create();
        $user2->name = 'new name';

        $this->actingAs($user1)->patch('/api/users/'.$user2->id, $user2->toArray());

        $this->assertDatabaseHas('users',[
            'name' => 'new name',
        ]);
    }

    public function test_an_authenticated_and_authorized_user_can_delete_a_user()
    {
        $user1 = User::factory()->create();
        $user1->assignRole('supervisor');

        $user2 = User::factory()->create(['name' => 'AABA']);

        $this->actingAs($user1)->delete('/api/users/'.$user2->id);

        $this->assertSoftDeleted($user2);
    }

}

