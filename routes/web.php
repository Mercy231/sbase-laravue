<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StripePaymentController;
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
    Route::get("/auth/google/redirect", "googleProvider");
    Route::get("/auth/google/callback", "googleCallback");
});
Route::controller(HelperController::class)->group(function () {
    Route::get("/countries", "getCountries");
    Route::post("/states", "getStates");
    Route::post("/cities", "getCities");
});
Route::controller(UserController::class)->group(function () {
    Route::get("/user", "getUser");
    Route::post("/changeAvatar", "changeAvatar");
    Route::post("/changeUserdata", "changeUserdata");
    Route::get("/admin/getUsers", "getUsers");
    Route::post("/admin/updateUserdata/{id}", "updateUserdata");
    Route::delete("/user/{id}", "destroy");
    ROute::post("/admin/getStatistics/{id}", "getStatistics");
    Route::post("/admin/notification/create", "createNotification");
    Route::post("/user/notification/read", "readNotification");
    Route::post("/user/notification/delete", "deleteNotification");
});
Route::controller(PostController::class)->group(function () {
    Route::get("/post", "getPosts");
    Route::post("/post/create", "create");
    Route::patch("/post/{id}", "update");
    Route::delete("/post/{id}", "destroy");
});
Route::controller(CommentController::class)->group(function () {
    Route::post("/comment/create", "create");
    Route::patch("/comment/{id}", "update");
    Route::delete("/comment/{id}", "destroy");
});
Route::controller(StripePaymentController::class)->group(function () {
    Route::post("/charge", "charge");
});
Route::get("/{any}", function () {
    return view("app");
})->where("any", ".*");
