<?php

namespace App\Http\Middleware;

use App\Helpers\JwtAuth;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $jwt = new JwtAuth();
        $checkToken = $jwt->checkToken($token);

        if ($checkToken):
            return $next($request);
        else:
            $data = [
                'status' => 'error',
                'message' => 'No tienes las credenciales de autorizacion',
                'code' => 401,
            ];

            return response()->json($data, $data['code']);
        endif;
    }
}
