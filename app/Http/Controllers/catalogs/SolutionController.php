<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusCatalogRequest;
use App\Models\Solution;

class SolutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        return response()->json([
            'solutions' => Solution::all(),
        ], 201);
    }

    public function store(StatusCatalogRequest $request)
    {
        return response()->json([
            'solucion' => Solution::create($request->all()),
            'message' => 'Solucion creado correctamente'
        ], 201);
    }

    public function show(Solution $solucione)
    {
        return response()->json(['solucion' => $solucione], 201);
    }

    public function update(Solution $solucione, StatusCatalogRequest $request)
    {
        $solucione->update($request->all());
        
        return response()->json([
            'solucion' => $solucione,
            'message' => 'Solución creado correctamente'
        ], 201);
    }

    public function destroy(Solution $solucione)
    {
        $solucione->delete();

        return response()->json([
            'message' => 'Se ha eliminado la solucion correctamente',
        ], 201);
    }
}