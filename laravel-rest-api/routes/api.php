<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [RegisteredUserController::class, 'registerUser'])
->middleware('api');
Route::post('/login', [RegisteredUserController::class, 'login'])
                ->middleware('api')
                ->name('login');
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
