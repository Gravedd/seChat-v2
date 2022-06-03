<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * POST /register
     * Register a user
     *
     * @param Request $request
     * @bodyparam name required |Its username
     * @bodyparam email required |Its user email
     * @bodyparam password required |Its a password
     * @bodyparam password-confirmination required |Its username
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string|max:32|min:3',
            'email' => 'required|string|unique:users,email|email',
            'password' => 'required|string|confirmed',
        ]);
        //create user in db
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);
        //set user token
        $token = $user->createToken('authtoken')->plainTextToken;
        $response = [
            'uid' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ];
        return response()->json($response, 201);
    }

    /**
     * POST /login
     * Login a user
     * Required user email & password
     *
     *
     * @param Request $request
     * @bodyparam email required |Its user email
     * @bodyparam password required |Its a password
     * @return \Illuminate\Http\JsonResponse
     * Return user token
     */
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
                'status' => $user->status,
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

    /**
     * POST /logout
     * logout the user
     * Required user token in header
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Return status
     */
    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();//remove all user tokens
        /*$request->user()->currentAccessToken()->delete();*///remove one user token
        return response()->json(['status' => 'logged out'], 201);
    }

    /**
     * POST /checkAuth
     * check user token
     * Required user token in header
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Return user information
     */
    public function checkauth(Request $request) {
        return response()->json($request->user());
    }


    public static function checkWsAuth($token) {
        return PersonalAccessToken::findToken($token);
    }

}
