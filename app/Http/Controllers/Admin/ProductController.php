<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {

    }

 
    public function show(Product $product)
    {
 
    }


    public function update(Request $request, Product $product)
    {
    
    }


    public function destroy(Product $product)
    {
    
    }
}
