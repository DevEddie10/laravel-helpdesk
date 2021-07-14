<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusCatalogRequest;
use App\Models\Module;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        return response()->json([
            'modules' => Module::all(),
        ], 201);
    }

    public function store(StatusCatalogRequest $request)
    {
        return response()->json([
            'message' => 'Modulo creado correctamente',
            'module' => Module::create($request->all()),
        ], 201);
    }

    public function show(Module $modulo)
    {
        return response()->json(['modulo' => $modulo]);
    }

    public function update(Module $modulo, StatusCatalogRequest $request)
    {
        $modulo->update($request->all());

        return response()->json([
            'message' => 'Modulo creado correctamente',
            'module' => $modulo,
        ]);
    }

    public function destroy(Module $modulo)
    {
        $modulo->delete();

        return response()->json([
            'message' => 'Se ha eliminado el modulo correctamente',
        ], 201);
    }
}