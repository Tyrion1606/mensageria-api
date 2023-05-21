<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = Str::random(60);
            $user->forceFill(['api_token' => hash('sha256', $token)])->save();

            return ['token' => $token];
        }

        return response()->json([
            'error' => 'Invalid email or password.',
        ], 401);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:2',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        // Agora, apenas criamos o usuário e o retornamos.
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }


}
