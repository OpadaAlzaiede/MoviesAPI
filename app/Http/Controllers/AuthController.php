<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Traits\ApiResponser;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request) {

        if(!Auth::attempt(['email' => $request['email'], 'password' => $request['password']]))
            return $this->error(401, Config::get('constants.LOGIN_ERROR_MESSAGE'));

        $user = auth()->user();

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], Config::get('constants.LOGIN_SUCCESS_MESSAGE'));
    }

    public function register(RegisterRequest $request, User $user) {

        $user->fill(
        [
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name
        ]
        );
        $user->save();

        $this->assignUserRole($user);

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], Config::get('constants.REGISTER_SUCCESS_MESSAGE'));
    }

    public function logout() {

        auth()->user()->tokens()->delete();

        return $this->success([], Config::get('constants.LOGOUT_SUCCESS_MESSAGE'));
    }

    private function assignUserRole($user) {

        $userRole = Role::findByName(Config::get('constants.USER_ROLE'), 'api');

        if(!$userRole) $userRole = Role::create(['name' => Config::get('constants.USER_ROLE'), 'guard_name' => 'api']);

        $user->assignRole($userRole);
    }
}
