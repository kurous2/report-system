<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\ResponseFormatter;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nrp' => ['required', 'numeric', 'digits:10', 'unique:users'],
            'nama' => ['required', 'string', 'max:255'],
            'prodi' => ['required', 'string', 'max:64'],
            'password' => ['required', 'string', 'min:8', 'max:32']
        ]);

        try {
            $user = User::create([                
                'email' => $request->email,
                'nrp' => $request->nrp,
                'nama' => $request->nama,
                'prodi' => $request->prodi,
                'password' => app('hash')->make($request->password)
            ]);

            return ResponseFormatter::success(
                [
                    'user' => $user
                ],
                'User Registered Successfully'
            );
                    
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'error' => $e,
                ],
                'User registration failed!'
            );
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);        

        if (! $token = auth()->attempt($credentials)) {
            return ResponseFormatter::error(
                null,
                'Login Failed',
            );
        } else {
            return ResponseFormatter::success(
                [
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,
                ],
                'Login Successfull'
            );
                  
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return ResponseFormatter::success(
            [
                'user' => auth()->user()
            ],
            'Get user data success'
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return ResponseFormatter::success(
            null,
            'Successfully logged out'
        );
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return ResponseFormatter::success(
            [
                'token' => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
            'Token Refresh Success'
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}