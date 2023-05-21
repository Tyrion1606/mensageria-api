<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    // método para autenticar um usuário
    public function login(Request $request)
    {
        // Pega apenas o email e a senha da requisição
        $credentials = $request->only('email', 'password');

        // Usa a facade Auth para tentar fazer o login. Se o login for bem sucedido, retorna true.
        if (Auth::attempt($credentials)) {
            // Pega o usuário autenticado
            $user = Auth::user();

            // Cria um token de API randomizado
            $token = Str::random(60);

            // Salva o token no banco de dados associado ao usuário autenticado
            // forceFill permite preencher atributos que não são mass assignable
            $user->forceFill(['api_token' => hash('sha256', $token)])->save();

            // Retorna o token como resposta
            return ['token' => $token];
        }

        // Se a autenticação falhar, retorna um erro 401 com a mensagem
        return response()->json([
            'error' => 'Invalid email or password.',
        ], 401);
    }

    // método para registrar um novo usuário
    public function register(RegisterRequest  $request)
    {
        // Valida os dados da requisição usando a Form Request customizada.
        // Se a validação falhar, uma resposta com erro de validação será automaticamente retornada.
        $validatedData = $request->validated();

        // Criptografa a senha do usuário antes de salvar
        $validatedData['password'] = bcrypt($request->password);

        // Cria um novo usuário com os dados validados e retorna como resposta.
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }
}
