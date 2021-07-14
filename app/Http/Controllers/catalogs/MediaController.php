<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCatalogRequest;
use App\Models\Media;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        return response()->json([
            'medios' => Media::all(),
        ], 201);
    }

    public function store(CreateCatalogRequest $request)
    {
        return response()->json([
            'message' => 'Medio creado correctamente.',
            'media' => Media::create($request->all()),
        ], 201);
    }

    public function show(Media $medio)
    {
        return response()->json([
            'media' => $medio,
        ], 201);
    }

    public function update(Media $medio, CreateCatalogRequest $request)
    {
        $medio->update($request->all());

        return response()->json([
            'message' => 'Medio editado correctamente',
            'medio' => $medio
        ], 201);
    }

    public function destroy(Media $medio)
    {
        $medio->delete();

        return response()->json([
            'message' => 'Se ha eliminado el medio correctamente',
        ], 201);
    }
}