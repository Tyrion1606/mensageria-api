<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // Valida os dados da requisição usando a Form Request customizada.
        // Se a validação falhar, uma resposta com erro de validação será automaticamente retornada.
        $validatedData = $request->validated();

        $credentials = $request->only('email', 'password');

        // Tenta fazer login. Se bem sucedido, retorna true.
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Obtem o usuário autenticado
            $token = $user->createToken('token_name'); // Cria um token usando a treat 'HasApiTokens' declarada no Model 'User'
            return ['token' => $token->plainTextToken];
        }

        // Se a autenticação falhar, retorna um erro 401 com a mensagem
        return response()->json([
            'error' => 'Invalid email or password.',
        ], 401);
    }

    public function logout()
    {
        $tokenId = Str::before(request()->bearerToken(), '|');
        auth()->user()->tokens()->where('id', $tokenId )->delete();

        // auth()->user()->currentAccessToken()->delete(); este método retornava 500 no teste, mas não no insomnia.

        return response()->json([], 204);
    }

    public function fullLogout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([], 204);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $userData = $request->only('name', 'email', 'password');

        $user = User::create($userData);

        return response()->json($user, 201);
    }
}
