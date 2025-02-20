<?php


namespace App\Collections;


use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class OrdersCollection
{
    public static function collection(Request $request )
    {
        $defaultSort = '-created_at';

        $defaultSelect = [
            'id',
            'client_id',
            'products_price',
            'total_cost',
            'address',
            'area_id',
            'driver_id',
            'taken_at',
            'delivered_at',
            'created_at',
            'updated_at',
        ];

        $allowedFilters = [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('client_id'),
            AllowedFilter::exact('driver_id'),
            'products_price',
            'total_cost',
            'address',
            'area_id',
            'taken_at',
            'delivered_at',
            'created_at',
            'updated_at',
        ];

        $allowedSorts = [
            'taken_at',
            'delivered_at',
            'updated_at',
            'created_at',
        ];

        $perPage = $request->limit  ? $request->limit : 50;

        $current_user_type = Auth()->user()->type;
        $query = '';
        if($current_user_type == 'client')
        {
            $query = QueryBuilder::for(Order::where('client_id', auth()->id()));
        }elseif($current_user_type == 'driver')
        {
            $query = QueryBuilder::for(Order::where('driver_id', auth()->id()));
        }else
        {
            $query = QueryBuilder::for(Order::class);
        }

        return $query
                ->select($defaultSelect)
                ->allowedFilters($allowedFilters)
                ->allowedSorts($allowedSorts)
                ->defaultSort($defaultSort)
                ->paginate($perPage);
    }

}
