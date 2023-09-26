<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route to get the authenticated user's data
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


    Route::post('login', [LoginController::class, 'login']);
   
   


// Route for user registration
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/events', [EventsController::class, 'store'])->middleware('auth');
Route::get('/Myevents', [EventsController::class, 'Myevents'])->middleware('auth');
Route::get('/Allevents', [EventsController::class, 'AllEvents']);



// Resourceful route for events

