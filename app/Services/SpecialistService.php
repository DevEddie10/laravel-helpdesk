<?php

namespace App\Services;

use App\Helpers\JwtAuth;

class SpecialistService
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

    public function authComments($specialist)
    {
        $decoded = $this->authToken();

        if (!$specialist):
            return [
                'message' => 'No existe el ticket',
                'code' => 404,
            ];
        endif;

        if ($specialist->assigned_id !== $decoded->sub):
            return [
                'message' => 'No tienes permiso para ejecutar esta accion',
                'code' => 401,
            ];
        endif;

        return [
            'ticket' => $specialist,
            'code' => 201,
        ];
    }
}