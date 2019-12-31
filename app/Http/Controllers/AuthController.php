<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return json
     */
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'     => 'required|min:4',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Authenticate the user
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = $this->guard()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'error'  => 'login_error',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
        ], 200)->header('Authorization', $token);
    }

    public function logout()
    {
        $this->guard()->logout();
        
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out !'
        ], 200);
    }

    public function refresh()
    {
        if(!$token = $this->guard()->refresh()) {
            return response()->json([
                'status' => 'error',
                'error' => 'refresh_token_error'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
        ], 200)->header('Authorization', $token);
    }

    public function getUser(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Return the authentication default guard
     *
     * @return mixed
     */
    private function guard()
    {
        return Auth::guard();
    }
}
