<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\DeliveryCost;
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
