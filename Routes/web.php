<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - forum
 * Last Updated - 9/09/2023
 */

use App\Controllers\AuthController;
use App\Controllers\DocumentController;
use App\Framework\Facades\Route;

Route::get("/login","login", AuthController::class);
Route::get("/register","register", AuthController::class);

Route::get("/documents/{id}/editor","edit", DocumentController::class);
Route::get("/documents","home",DocumentController::class);
Route::get("/documents/new","newDocument",DocumentController::class);