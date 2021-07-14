<?php

namespace App\Http\Controllers\catalogs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusCatalogRequest;
use App\Models\State;

class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    public function index()
    {
        return response()->json(['states' => State::all()], 201);
    }

    public function store(StatusCatalogRequest $request)
    {
        return response()->json([
            'state' => State::create($request->all()),
            'message' => 'Estado creado correctamente.',
        ], 201);
    }

    public function show(State $estado)
    {
        return response()->json(['state' => $estado]);
    }

    public function update(State $estado, StatusCatalogRequest $request)
    {
        $estado->update($request->all());

        return response()->json([
            'state' => $estado,
            'message' => 'Estado editado correctamente',
        ], 201);
    }

    public function destroy(State $estado)
    {
        $estado->delete();

        return response()->json([
            'message' => 'Se ha eliminado correctamente',
        ], 201);
    }

    public function allPriorityState($id)
    {
        $priorities = State::where([
            'status' => $id,
        ])->get(['id', 'name']);

        return response()->json(['priorities' => $priorities], 201);
    }
}