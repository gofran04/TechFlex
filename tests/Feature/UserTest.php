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

}

