<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for ycd wour application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Public routes
Route::post('/signup', [AuthController::class, 'signup']);

// Private routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/create/openapikey', [AuthController::class, 'createOpenApiKey']);
    Route::post('/create/adminapikey', [AuthController::class, 'createAdminApiKey']);
    Route::post('/create/adminuser', [AuthController::class, 'createAdminUser']);
});
