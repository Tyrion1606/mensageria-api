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

Route::prefix('auth')->group(function(){
    // Rota para fazer login (não precisa de autenticação)
    Route::post('/login', 'App\Http\Controllers\AuthController@login');

    // Rota para fazer logout (precisa de autenticação)
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->middleware('auth:sanctum');

    // Rota para fazer logout de TODOS os tokens do usuário atualmente logado (precisa de autenticação)
    Route::post('/logout/all', 'App\Http\Controllers\AuthController@fullLogout')->middleware('auth:sanctum');

    // Rota para fazer registro (não precisa de autenticação)
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
});

// Rota para envio de mensagens (precisa de autenticação)
Route::post('/{channel}', 'App\Http\Controllers\MessagingController@send')->middleware('auth:sanctum');

