<?php

namespace App\Http\Controllers\role;

use App\Helpers\JwtAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
    private $token;

    public function __construct()
    {
        $this->middleware('api.auth');
        $this->token = request()->header('Authorization');
    }

    public function index()
    {
        $jwt = new JwtAuth();
        $decoded = $jwt->checkToken($this->token, true);

        $roles = Role::where([
            'id' => 3,
        ])->with('user', fn($query) => $query->where('id', '!=', $decoded->sub))->get();

        return response()->json(['roles' => $roles], 201);
    }

    public function store(StoreRoleRequest $request)
    {
        return response()->json([
            'message' => 'Se ha creado el role correctamente',
            'role' => Role::create($request->all())
        ]);    
    }

    public function show(Role $role)
    {
        return response()->json(['role' => $role], 201);
    }

    public function update(Role $role, StoreRoleRequest $request)
    {
        $role->update($request->all());

        return response()->json(['role' => $role], 201);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Se ha eliminado el role correctamente']);
    }
}