<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function registerUser(Request $request): JsonResponse
    {
        $validateUser = Validator::make($request->all(),
        [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status'=>false,
                'message' => "validation_error",
                'errors' => $validateUser->errors()
            ],422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //event(new Registered($user));
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                    'access_token' => $token,
                        'token_type' => 'Bearer'
        ],200);
    }


public function login(Request $request): JsonResponse
{
    $validateUser = Validator::make($request->all(),
    [

        'email' => ['required', 'email', 'max:255'],
        'password' => ['required'],
    ]);

    if($validateUser->fails()){
        return response()->json([
            'status'=>false,
            'message' => "validation_error",
            'errors' => $validateUser->errors()
        ],422);
    }

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
        'message' => 'Invalid login details'
                ], 401);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);

}
}
