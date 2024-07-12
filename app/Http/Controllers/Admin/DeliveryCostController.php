<?php

namespace App\Http\Controllers\Admin;
use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Collections\DeliveryCostCollection;
use App\Http\Resources\DeliveryCostResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\DeliveryCost\StoreDeliveryCostRequest;
use App\Http\Requests\DeliveryCost\UpdateDeliveryCostRequest;



class DeliveryCostController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-all-delivery-costs');
        return DeliveryCostResource::collection(DeliveryCostCollection::collection($request))->collection; 
    }

    public function store(StoreDeliveryCostRequest $request)
    {
        $this->authorize('create-delivery-cost');
        $delivery_cost = DeliveryCost::create($request->validated());

        return (new DeliveryCostResource($delivery_cost))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        
    }

    public function show(DeliveryCost $deliveyCost)
    {
        $this->authorize('view-delivery-cost');
        return new DeliveryCostResource($deliveyCost);
    }

    public function update(UpdateDeliveryCostRequest $request, DeliveryCost $deliveryCost)
    {
        $this->authorize('edit-delivery-cost');
        $deliveryCost->update($request->validated());

        return (new DeliveryCostResource($deliveryCost->refresh()))
                ->response()
                ->setStatusCode(Response::HTTP_ACCEPTED);
        
    }

    public function destroy(DeliveryCost $deliveyCost)
    {
        $this->authorize('delete-delivery-cost');
        $deliveyCost->delete();
        return response()->json([
            'message' => ('Delivery Cost For This Area Has Been successfully deleted')
        ]);
    }
}
