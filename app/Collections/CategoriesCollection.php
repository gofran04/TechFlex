<?php


namespace App\Collections;


use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class CategoriesCollection
{
    public static function collection(Request $request)
    {
        $defaultSort = '-created_at';

        $defaultSelect = [
            'id',
            'name',
            'created_at',
            'updated_at',
        ];

        $allowedFilters = [
            AllowedFilter::exact('id'),
            'name',
            'created_at',
            'updated_at',
        ];

        $perPage = $request->limit  ? $request->limit : 50;

        return QueryBuilder::for(Category::class)
            ->select($defaultSelect)
            ->allowedFilters($allowedFilters)
            ->defaultSort($defaultSort)
            ->paginate($perPage);
    }

}
