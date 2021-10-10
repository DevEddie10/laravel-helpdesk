<?php

namespace App\Services;

use App\Helpers\JwtAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    private $token;

    public function __construct()
    {
        $this->token = request()->header('Authorization');
    }

    public function authToken()
    {
        $jwt = new JwtAuth();
        return $jwt->checkToken($this->token, true);
    }

    public function authUser($usuario)
    {
        $decode = $this->authToken();

        return $usuario->where([
            'id' => $decode->sub,
        ])
            ->orderBy('id', 'desc')
            ->with('roles')->first();
    }

    public function updateAuthUser($request, $usuario)
    {
        $validated = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
            'email' => [
                'required', 'string', 'email', 'max:50',
                Rule::unique('users', 'email')->ignore($usuario->id)
            ],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Todos los campos son obligatorios',
                'errors' => $validated->errors(),
            ], 404);
        }

        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Datos editados correctamente.',
            'user' => $usuario->with('roles')->find($usuario->id),
        ], 201);
    }

    public function updateAuthPassword($user, $request)
    {
        $validated = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'El campo es obligatorio',
                'errors' => $validated->errors()
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Contraseña editada correctamente',
            'user' => $user
        ], 201);
    }

    public function uploadFile($request)
    {
        $decoded = $this->authToken(); 
        $user = User::find( $decoded->sub);
        Storage::delete($user->image);
        
        $image = $request->file('image')->store('images');

        $user->update(['image' => $image]);

        return [
            'message' => 'Avatar actualizado',
            'user' => $user
        ];
    }
}