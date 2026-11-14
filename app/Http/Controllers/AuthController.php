<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device' => ['required', 'string'],
        ]);

        $user = User::firstWhere('email', $data['email']);

        if (! $user || ! Hash::check(
            $data['password'], $user->password
        )) {
            abort(401, 'Identifiants incorrects.');
        }

        $token = $user->createToken($data['device']);

        return ['token' => $token->plainTextToken];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
