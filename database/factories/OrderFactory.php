<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DeliveryCost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id'             => User::factory(),
            'address'               => 'Khartoum,Jabra',
            'area_id'               => DeliveryCost::first(),
            'delivery_latitude'     => fake()->latitude(),
            'delivery_longitude'    => fake()->longitude(),
        ];
    }
}
