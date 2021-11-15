<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;

class UserAuthController extends Controller
{
    protected $service;
   
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->uploadFile($request);
    }

    public function update(StoreUserRequest $request, User $usuario)
    {
        return $this->service->updateAuthUser($request, $usuario);
    }

    public function editPassword(User $user, Request $request)
    {
        return $this->service->updateAuthPassword($user, $request);
    }
}