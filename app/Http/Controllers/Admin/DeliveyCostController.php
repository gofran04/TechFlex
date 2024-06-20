<?php

namespace App\Http\Controllers\Admin;
use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collections\DeliveyCostCollection;
use App\Http\Resources\DeliveyCostResource;


class DeliveyCostController extends Controller
{
    public function index(Request $request)
    {
        return DeliveyCostResource::collection(DeliveyCostCollection::collection($request))->collection; 
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
