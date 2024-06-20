<?php


namespace App\Collections;


use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class DeliveryCostCollection
{
    public static function collection(Request $request)
    {
        $defaultSort = '-created_at';

        $defaultSelect = [
            'id',
            'area',
            'delivery_cost',
            'created_at',
            'updated_at',
        ];

        $allowedFilters = [
            AllowedFilter::exact('id'),
            'area',
            'delivery_cost',
            'created_at',
            'updated_at',
        ];

        $perPage = $request->limit  ? $request->limit : 50;

        return QueryBuilder::for(DeliveryCost::class)
            ->select($defaultSelect)
            ->allowedFilters($allowedFilters)
            ->defaultSort($defaultSort)
            ->paginate($perPage);
    }

}
