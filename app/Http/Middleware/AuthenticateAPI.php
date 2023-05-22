<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthenticateAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retira o prefixo 'Bearer ' do token
        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        // Converte o token para o formato sha256 para comparar com o que está no banco de dados
        $token = hash('sha256', $token);

        // Procura um usuário com esse token
        $user = User::where('api_token', $token)->first();

        // Se não existir um usuário com esse token, retorna um erro 401
        if (!$user) {
            return response()->json('Unauthorized', 401);
        }
        // Se o usuário foi encontrado, adiciona o usuário à Request para que possa ser acessado nos controladores
        $request->user = $user;

        // Continua com a próxima etapa do pipeline de solicitação
        return $next($request);
    }
}
