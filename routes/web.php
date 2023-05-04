<?php

use App\Http\Controllers\HelperController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post("/signup", "signup");
    Route::post("/login", "login");
    Route::get("/logout", "logout");
});
Route::controller(HelperController::class)->group(function () {
    Route::get("/countries", "getCountries");
    Route::post("/states", "getStates");
    Route::post("/cities", "getCities");
});
Route::controller(UserController::class)->group(function () {
    Route::get("/user", "getUser");
});
Route::get("/{any}", function () {
    return view("app");
})->where("any", ".*");
