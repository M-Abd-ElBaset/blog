<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(UserLoginRequest $request) : string{
        $request->validated($request->all());
        if(!Auth::attempt($request->only('email','password'))){
            return $this->error('', 401, "Invalid Credentials");
        }
        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('Token of '. $user->name);

        return $this->success([
            'user'=>$user,
            'token'=>$token->plainTextToken
        ]);
    }

    public function register(UserStoreRequest $request) : string{
        $request->validated($request->all());
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        $token = $user->createToken('Token of '. $user->name);

        return $this->success([
            'user'=>$user,
            'token'=>$token->plainTextToken
        ]);
    }

    public function logout() : string{
        auth('sanctum')->user()->currentAccessToken()->delete();
        return $this->success('', 200, "You successfully logged out. Thank You");
    }
}
