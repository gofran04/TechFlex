<?php

namespace App\Http\Controllers\Admin;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;

class StoreController extends Controller
{
    public function index()
    {
        return StoreResource::collection(Store::all());
    }

    public function show(Store $store)
    {
        return new StoreResource($store);
    }

    public function update(Request $request, Store $store)
    {
        //
    }
}
