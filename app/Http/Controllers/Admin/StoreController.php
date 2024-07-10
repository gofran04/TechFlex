<?php

namespace App\Http\Controllers\Admin;

use App\Models\Store;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Http\Requests\Store\UpdateStoreRequest;
use Symfony\Component\HttpFoundation\Response;

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

    public function update(UpdateStoreRequest $request, Store $store)
    {
        $this->authorize('edit-store');
        $store->update($request->validated());

        return (new StoreResource($store->refresh()))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
