<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    }

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
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user' => $user,
        ], 201);
    }

    public function show(User $usuario)
    {
        return response()->json(['user' => $usuario]);
    }

    public function update(User $usuario, StoreUserRequest $request)
    {
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $usuario]);
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return response()->json(['message' => 'Se ha eliminado el usuario correctamente']);
    }
}