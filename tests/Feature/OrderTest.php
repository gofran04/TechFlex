<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\DeliveryCost;
use App\Models\Product;
use Database\Seeders\GeneralManagerSeeder;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\ProductSeeder;


class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            GeneralManagerSeeder::class,
            PermissionsSeeder::class,
            ProductSeeder::class,
        ]);
    }

    public function test_an_authenticated_and_authorized_users_can_read_all_orders()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        $order1 = $this->createOrder();
        $order2 = $this->createOrder();

        $this->get('/api/orders');
        $this->assertDatabaseCount('orders', 2);
    }

    public function test_an_authenticated_and_authorized_users_can_read_a_order()
    {
        $user = User::factory()->create();
        $user->assignRole('supervisor');
        $this->actingAs($user);

        $order = $this->createOrder();
        $response = $this->get('/api/orders/'.$order->id);

        $response->assertSuccessful();
        $this->assertDatabaseCount('orders', 1);
        $response->assertJson([
               'data' => [
                   'id'             => $order->id,                
                   'client_id'      => $order->client_id,
                   'products_price' => $order->products_price,
                   'address'        => $order->address,
               ]
           ]);
    }

    public function test_an_authenticated_and_authorized_user_can_create_an_order()
    {
        $user = User::factory()->create(['type' => 'client']);
        $user->assignRole('client');

        $product = Product::first();

        $data = [
            'address'        => 'order address',
            'products' =>   [
                [
                    'product_id' => $product->id,
                    'quantity'   => 5
                ]
             ]
        ];

        $response = $this->actingAs($user)->post('/api/orders', $data);

        $this->assertDatabaseCount('orders', 1);
        $response->assertCreated();
        $response->assertJson([
            'data' => [
            'address' => $data['address'],
        ]]);
    }

    public function test_an_authenticated_and_authorized_user_can_update_an_order()
    {
        $supervisor = User::factory()->create(['type' => 'supervisor']);
        $supervisor->assignRole('supervisor');

        $driver = User::factory()->create(['type' => 'driver']);
        $driver->assignRole('driver');

        $order = $this->createOrder();
        $data = [
            'status'    => 'in process',
            'driver_id' => $driver->id
        ];

        $response = $this->actingAs($supervisor)->patch('/api/orders/'.$order->id, $data);
        $response->assertJson([
            'data' => [
            'status' => $data['status'],
        ]]);
    }

    public function test_unauthorized_users_can_not_create_or_update_an_order()
    {
        $driver = User::factory()->create(['type' => 'driver']);
        $driver->assignRole('driver');

        $order = $this->createOrder();

        $product = Product::first();
        $data1 = [
            'address'        => 'order address',
            'products' =>   [
                [
                    'product_id' => $product->id,
                    'quantity'   => 5
                ]
             ]
        ];

        $data2 = [
            'status'    => 'in process',
            'driver_id' => $driver->id
        ];

        $this->actingAs($driver)->post('/api/orders',$data1)->assertForbidden();
        $this->actingAs($driver)->patch('/api/orders/'.$order->id, $data2)->assertForbidden();
    }


    protected function createOrder()
    {
        $order_product = OrderProduct::factory()->create();
        $order = Order::find($order_product->order_id);

        $delivery_cost = DeliveryCost::where('id',$order->area_id)->first()->delivery_cost;

        $products_price = OrderProduct::Where('order_id',$order->id)->sum('total_price');

        $order->update([
            'products_price' => $products_price,
            'total_cost'    => $products_price + $delivery_cost,
        ]);

        return $order;
    }
}
