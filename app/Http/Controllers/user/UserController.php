<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('admin_23'),
            'status' => 0,
        ]);

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
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

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

    public function editUser(UserService $service, Request $request, User $user)
    {
        return $service->updateAuthUser($request, $user);
    }

    public function editPassword(UserService $service, User $user, Request $request)
    {
        return $service->updateAuthPassword($user, $request);
    }

    public function upload(UserService $service, Request $request)
    {
        return $service->uploadFile($request);
    }
}