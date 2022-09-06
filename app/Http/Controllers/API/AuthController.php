<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Registers user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $password = Str::random(12);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password)
            ]);

            return new JsonResponse([
                'message' => 'Successfully Registered',
                'password' => $password,
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 422);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return new JsonResponse(['message' => 'Invalid credentials'], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('API TOKEN')->plainTextToken;

            return new JsonResponse([
                'message' => 'Logged In Successfully',
                'token' => $token,
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 422);
        }
    }
}
