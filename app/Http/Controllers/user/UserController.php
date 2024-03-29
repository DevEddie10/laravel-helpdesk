<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')
            ->with('roles')->get();

        return response()->json(['users' => $users], 201);
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
        return response()->json([
            'user' => $usuario->with('roles')->find($usuario->id),
        ]);
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