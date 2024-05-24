<?php


namespace App\Collections;


use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class OrdersCollection
{
    public static function collection(Request $request)
    {
        $defaultSort = '-created_at';

        $defaultSelect = [
            'id',
            'client_id',
            'products_price',
            'address',
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
            'address',
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

        return QueryBuilder::for(Order::class)
            ->select($defaultSelect)
            ->allowedFilters($allowedFilters)
            ->allowedSorts($allowedSorts)
            ->defaultSort($defaultSort)
            ->paginate($perPage);
    }

}
