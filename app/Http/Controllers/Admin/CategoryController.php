<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;



class CategoryController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        
    }

    public function show(Category $category)
    {
    }

    public function update(Request $request, Category $category)
    {
       
    }


    public function destroy(Category $category)
    {

    }
}
