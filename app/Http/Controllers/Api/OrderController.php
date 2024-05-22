<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $this->authorize('view-all-orders');
        $orders = Order::all();
        $current_user_type = Auth()->user()->type;
        if($current_user_type == 'client' ||  $current_user_type == 'driver')
        {
            $orders = Order::Where('client_id',auth()->id())->get();
            if(sizeof($orders) > 0)
            {
                return OrderResource::collection($orders);

            }else{ 
                return response()->json([
                    'message'      => 'You Have Not Orders To View!',
                ]); 
            }       
        }
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create-order');
        return DB::transaction(function () use ($request) 
        {
            $order = Order::Create(['client_id' => auth()->id(),'address' => $request->address]);

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
        $this->authorize('view-order');

        $current_user_type = Auth()->user()->type;
        if($current_user_type == 'client' ||  $current_user_type == 'driver')
        {
            if($order->client_id == auth()->id())
            {
                return new OrderResource($order);
            }else{
                return response()->json([
                    'message'      => 'You Have Not Orders To View!',
                ]); 
            }       
        }

        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('edit-order');
        $order->update($request->validate());

        switch ($request->status) {
            case 'in process':
                $order->update(['status' => 'in process', 'driver_id' => $request->driver_id]);
                break;            
            case 'out for delivery':
                $order->update(['status' => 'out for delivery', 'taken_at' => Carbon::now()]);
                break;
            case 'delivered':
                $order->update(['status' => 'delivered','delivered_at' => Carbon::now()]);
            break;
            case 'canceled':
                $order->update(['status' => 'canceled']);
            break;
        }

        return (new OrderResource($order))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
        
    }
}
