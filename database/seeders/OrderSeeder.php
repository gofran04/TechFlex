<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Order;
use App\Models\DeliveryCost;


class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order_product = OrderProduct::factory()->create();
        $order = Order::find($order_product->order_id);

        $delivery_cost = DeliveryCost::where('id',$order->area_id)->first()->delivery_cost;

        $products_price = OrderProduct::Where('order_id',$order->id)->sum('total_price');

        $order->update([
            'products_price' => $products_price,
            'total_cost'    => $products_price + $delivery_cost,
        ]);
    }
}
