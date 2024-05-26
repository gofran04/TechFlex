<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Collections\ProductsCollection;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        return ProductResource::collection(ProductsCollection::collection($request))->collection;
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create-product');
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
        $this->authorize('edit-product');
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
        $this->authorize('delete-product');
        $product->delete();
        $product->clearMediaCollection('product_pic');
        return response()->json([
            'message' => ('Product successfully deleted')
        ]);
    }
}
