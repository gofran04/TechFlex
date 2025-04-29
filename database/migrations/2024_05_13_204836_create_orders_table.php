<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('driver_id')->nullable()->references('id')->on('users');
            $table->float('products_price')->default(0);
            $table->float('total_cost')->nullable();
            $table->string('address');
            $table->foreignId('area_id')->references('id')->on('delivery_costs');
            $table->double('delivery_latitude')->nullable();
            $table->double('delivery_longitude')->nullable();
            $table->set('status', ['initiated','in process', 'out to delivery', 'delivered', 'canceled'])->default('initiated');
            $table->timestamp('taken_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
