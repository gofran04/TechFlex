<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;



class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
       $category->update($request->validated());

        return (new CategoryResource($category->refresh()))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => ('Category successfully deleted')
        ]);
    }
}
