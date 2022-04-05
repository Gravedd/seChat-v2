<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string|',
            'email' => 'required|string|unique:users,email|email',
            'password' => 'required|string|confirmed',
        ]);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
        $token = $user->createToken('authtoken')->plainTextToken;
        $response = [
            'uid' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ];
        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        //check email
        $user = User::where('email', $fields['email'])->first();
        //check pass
        if (isset($user) AND Hash::check($fields['password'], $user->password)) {
            //hash and email true
            $token = $user->createToken('authtoken')->plainTextToken;
            return response()->json([
                'status' => true,
                'uid' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
            ], 201);
        } else {
            //no auth
            return response()->json([
                'status' => false,
                'message' => 'bad credentials',
            ], 401);
        }


    }


    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();//Удаление всех токенов
        /*$request->user()->currentAccessToken()->delete();*///Удалить текущий токен
        return response()->json(['status' => 'logged out']);
    }

}
