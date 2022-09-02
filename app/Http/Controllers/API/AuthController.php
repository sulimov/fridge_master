<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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

            return new JsonResponse(['password' => $password]);
        } catch (\Throwable $exception) {
            return new JsonResponse($exception->getMessage(), 422);
        }
    }

    public function login(LoginRequest $request)
    {

    }
}
