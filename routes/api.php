<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota para fazer login (não precisa de autenticação)
Route::post('/auth', 'App\Http\Controllers\AuthController@login');

// Rota para fazer registro (não precisa de autenticação)
Route::post('/register', 'App\Http\Controllers\AuthController@register');

// Rota para envio de mensagens (precisa de autenticação)
Route::post('/{channel}', 'App\Http\Controllers\MessagingController@send')->middleware('auth.api');       //que outra forma os usuários podem ser cadastrados?

