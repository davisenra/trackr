<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $request->user(),
        ]);
    }

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'message' => 'Authenticated',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function register(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create($credentials);

        return response()->json([
            'status' => true,
            'message' => 'Registered successfully',
        ], Response::HTTP_CREATED);
    }
}
