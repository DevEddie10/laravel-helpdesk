<?php

namespace App\Http\Controllers\login;

use App\Helpers\JwtAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        $jwtAuth = new JwtAuth();

        $user = User::where([
            'email' => $request->email,
        ])->first();

        if (!$user):
            return response()->json([
                'statusCode' => 401,
                'error' => [
                    'type' => 'Error de validación',
                    'message' => 'Email incorrecta'
                ]
            ], 401);
        endif;

        if (Hash::check($request->password, $user->password)):
            $data = [
                'statusCode' => 200,
                'token' => $jwtAuth->singup($user)
            ];
        else:
            $data = [
                'statusCode' => 401,
                'error' => [
                    'type' => 'Error de validación',
                    'message' => 'Contraseña incorrecta'
                ]
            ];
        endif;

        return response()->json($data, $data['statusCode']);
    }
}