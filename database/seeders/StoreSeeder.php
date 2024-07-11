<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::fake('logos');
        $file = UploadedFile::fake()->image('TeckflexLogo.jpg');

        $store = Store::create([
            'name'              => "TechFlex",
            'email'             => 'Techflex@mail.com',
            'address'           => 'TechFlex Address- street 017',
            'phone'             => '0123456789',
        ]);

        $store->addMedia($file)->toMediaCollection('logo');
    }
}
