<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LoginController;
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

/*
Route::middleware('auth:api')->group(function(){
  Route::get('user', [AuthController::class,'authenticatedUserDetails']);
});
*/
//Route::post('login', [LoginController::class,'authenticate'])->name('login');
//Route::post('login', [AuthController::class,'login'])->name('login');
Route::post('register', [AuthController::class,'register'])->name('register');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
  Route::get('getCardByName', [AuthController::class,'getCardByName'])->name('getCardByName');
  Route::get('getCardsByColor', [AuthController::class,'getCardsByColor'])->name('getCardsByColor');
  Route::get('getCardsBySet', [AuthController::class,'getCardsBySet'])->name('getCardsBySet');
  Route::get('getCardsByType', [AuthController::class,'getCardsByType'])->name('getCardsByType');
  Route::get('getCardsByRarity', [AuthController::class,'getCardsByRarity'])->name('getCardsByRarity');
});