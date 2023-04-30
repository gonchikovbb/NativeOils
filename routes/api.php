<?php

use App\Client\RMaslaClient;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** Авторизация */
Route::get('login', [RMaslaClient::class,'login']);

/** Получение всех контактов */
Route::get('contact', [RMaslaClient::class,'getContacts']);

/** Редактирование контакта по id */
Route::middleware('guest')->post('contact/{id}', [RMaslaClient::class,'updateContact']);

/** Удаление контакта по id */
Route::middleware('guest')->post('delete-contact/{id}', [RMaslaClient::class,'deleteContact']);

