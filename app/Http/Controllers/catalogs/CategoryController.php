<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        return response()->json([
            'categories' => Category::all(),
        ], 201);
    }

    public function store(CreateCategoryRequest $request)
    {
        return response()->json([
            'category' => Category::create($request->all()),
            'message' => 'La categoría se ha creado correctamente',
        ], 201);
    }

    public function show(Category $categoria)
    {
        return response()->json([
            'category' => $categoria,
        ], 201);
    }

    public function update(Category $categoria, CreateCategoryRequest $request)
    {
        $categoria->update($request->all());

        return response()->json([
            'category' => $categoria,
            'message' => 'La categoría se ha editado correctamente',
        ], 201);
    }

    public function destroy(Category $categoria)
    {
        $categoria->delete();

        return response()->json([
            'message' => 'Se ha eliminado la categoría correctamente',
        ], 201);
    }
}