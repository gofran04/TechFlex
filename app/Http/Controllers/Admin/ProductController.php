<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create-product');
        $product = Product::create($request->validated());
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

 
    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('edit-product');
        $product->update($request->validated());

        return (new ProductResource($product->refresh()))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }


    public function destroy(Product $product)
    {
        $this->authorize('delete-product');
        $product->delete();
        return response()->json([
            'message' => __('Product successfully deleted')
        ]);
    }
}
