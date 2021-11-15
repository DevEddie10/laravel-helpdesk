<?php

namespace App\Services;

use App\Helpers\JwtAuth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function updateAuthUser($request, $user)
    {
        $user->update($request->all());

        return response()->json([
            'message' => 'Datos editados correctamente.',
            'user' => $user->with('roles')->find($user->id),
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

        $user->update($request->all());

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