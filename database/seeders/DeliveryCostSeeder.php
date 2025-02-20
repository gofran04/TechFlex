<?php

namespace Database\Seeders;

use App\Models\DeliveryCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryCost::factory()->create(['area' => 'Area 1', 'delivery_cost' => 1000.00]);
        DeliveryCost::factory()->create(['area' => 'Area 2', 'delivery_cost' => 2000.00]);
        DeliveryCost::factory()->create(['area' => 'Area 3', 'delivery_cost' => 3000.00]);
    }
}
