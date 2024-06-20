<?php

namespace Database\Seeders;

use App\Models\DeliveyCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveyCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveyCost::factory()->create(['area' => 'Area 1', 'delivery_cost' => 1000]);
        DeliveyCost::factory()->create(['area' => 'Area 1', 'delivery_cost' => 2000]);
        DeliveyCost::factory()->create(['area' => 'Area 1', 'delivery_cost' => 3000]);
        
    }
}
