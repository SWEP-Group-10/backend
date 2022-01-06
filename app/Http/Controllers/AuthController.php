<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $username = "admin";
        $password = "swep-timetable";

        if ($username === $request->username && $password === $request->password) {
            return response()->json(['bearer_token' => config('app.api_key')]);
        }

        return response()->json(['error' => 'invalid username/password combination'], 403);
    }
}
