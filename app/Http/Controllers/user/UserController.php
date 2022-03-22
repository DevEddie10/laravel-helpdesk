<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return UserCollection::make(User::orderBy('id', 'desc')->with('roles')->get());
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->attach($request->role);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user' => $user,
        ], 201);
    }

    public function show(User $usuario)
    {
        $result = UserResource::make($usuario->with('roles')->find($usuario->id));

        return response()->json([
            'statusCode' => 200,
            'data' => $result,
        ], 200);
    }

    public function update(User $usuario, StoreUserRequest $request)
    {
        $usuario->update($request->all());
        $usuario->roles()->sync($request->role);

        return response()->json([
            'message' => 'Usuario editado correctamente.',
            'user' => $usuario,
        ], 201);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return response()->json(['message' => 'Se ha eliminado el usuario correctamente'], 201);
    }
}
