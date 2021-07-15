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
            return response()->json(['message' => 'Email incorrecto'], 404);
        endif;

        if (Hash::check($request->password, $user->password)):
            $data = [
                'token' => $jwtAuth->singup($user),
                'code' => 201
            ];
        else:
            $data = [
                'message' => 'Contraseña incorrecta',
                'code' => 404
            ];
        endif;

        return response()->json($data, $data['code']);
    }
}