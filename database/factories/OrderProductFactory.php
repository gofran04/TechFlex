<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\DeliveryCost;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderProduct>
 */
class OrderProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order = Order::factory()->create();
        $product = Product::first();
        $quantity = 2;

        return [
            'order_id'        => $order,
            'product_id'      => $product->id,
            'quantity'        => $quantity,
            'product_price'   => $product->price,
            'total_price'     => $quantity * $product->price,             
        ];
    }
}
