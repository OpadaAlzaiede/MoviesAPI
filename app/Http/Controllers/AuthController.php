<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request) {

        if(!Auth::attempt(['email' => $request['email'], 'password' => $request['password']]))
            return $this->error(401, 'Credentials not match');

        $user = auth()->user();

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 'You have logged in successfully');
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

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 'You have registered successfully');
    }

    public function logout() {

        auth()->user()->tokens()->delete();

        return $this->success([], 'You Have Successfully Logged out');
    }
}
