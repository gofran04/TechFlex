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
        $product = Product::create($request->validated());
        if ($request->has('product_pic')) 
        {
            $product->addMedia($request->file('product_pic'))->toMediaCollection('product_pic');
        }

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
        $product->update($request->validated());
        if ($request->has('product_pic')) 
        {
            if ($request->input('product_pic') !== $product->product_pic->file_name) 
            {
                $product->clearMediaCollection('product_pic');
            }
            $product->addMedia($request->file('product_pic'))->toMediaCollection('product_pic');
        }

        return (new ProductResource($product->refresh()))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }


    public function destroy(Product $product)
    {
        $product->delete();
        $product->clearMediaCollection('product_pic');
        return response()->json([
            'message' => ('Product successfully deleted')
        ]);
    }
}
