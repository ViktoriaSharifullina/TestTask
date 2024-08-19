<?php

use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/guests', [GuestController::class, 'index']);
Route::post('/api/guests', [GuestController::class, 'store']);
Route::get('/api/guests/{id}', [GuestController::class, 'show']);
Route::put('/api/guests/{id}', [GuestController::class, 'update']);
Route::delete('/api/guests/{id}', [GuestController::class, 'destroy']);
