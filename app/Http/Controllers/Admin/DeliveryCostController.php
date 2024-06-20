<?php

namespace App\Http\Controllers\Admin;
use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collections\DeliveryCostCollection;
use App\Http\Resources\DeliveryCostResource;


class DeliveryCostController extends Controller
{
    public function index(Request $request)
    {
        return DeliveryCostResource::collection(DeliveryCostCollection::collection($request))->collection; 
    }

    public function store(Request $request)
    {
        //
    }

    public function show(DeliveryCost $deliveyCost)
    {
        //
    }

    public function update(Request $request, DeliveryCost $deliveyCost)
    {
        //
    }

    public function destroy(DeliveryCost $deliveyCost)
    {
        //
    }
}
