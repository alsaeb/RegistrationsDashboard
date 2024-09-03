<?php

use App\Http\Controllers\UserController;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'data']);
Route::post('/', [UserController::class, 'data'])->name('filterData');
