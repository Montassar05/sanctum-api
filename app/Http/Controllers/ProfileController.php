<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            // Unique… sauf pour sa propre ligne.
            'email' => ['required', 'email',
                Rule::unique('users')->ignore($user),
            ],
        ]);

        $user->update($data);

        return new UserResource($user);
    }
}
