<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    // const MODEL = "App\AuthController";

    // use RESTActions;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('role', ['except' => ['login']]);
    }

    public function test() {
        return response()->json(["msg" => "test ok !!"]);
    }
    public function login(Request $request)
    {
        // $email = $request->input('email');
        // $password = $request->input('password');
        // $credentials[] = $email;
        // $credentials[] = $password;
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
            'user' => Auth::guard('api')->user(),
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    public static function me() {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["msg" => 'user_not_found'], 404);
        }
        return Auth::guard('api')->user();
    }
    
    public function infos() {
        return response()->json(['msg' => 'Data ok!']);
    }
}
