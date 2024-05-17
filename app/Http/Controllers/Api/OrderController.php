<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('view-all-orders');
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create-order');
        return DB::transaction(function () use ($request) 
        {
            $order = Order::Create(['client_id'=> auth()->id()]);

            $all_products = $request->products;
            foreach ($all_products as $product)
            {
                $product_model = Product::find($product['product_id']);
                $data = 
                [
                    'order_id'      => $order->id,
                    'product_id'    => $product['product_id'],
                    'quantity'      => $product['quantity'],
                    'product_price' => $product_model->price,
                    'total_price'   => $product_model->price * $product['quantity'],
                ]; 

                OrderProduct::create($data);
            }
            $products_price = OrderProduct::Where('order_id',$order->id)->sum('total_price');
            $order->update([ 'products_price' => $products_price]);
            
            return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
        });
    }

    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }
}
