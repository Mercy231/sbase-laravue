<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function signup (Request $request) : JsonResponse
    {
        $credentials = $request->only([
            "full_name",
            "email",
            "password",
            "password_confirmation",
            "country",
            "state",
            "city"
        ]);
        $validator = Validator::make($credentials, [
            "full_name" => "required|min:2",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed|min:6",
            "password_confirmation" => "required",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json(["success" => false, "error" => $validator->errors()->first()]);
        }
        if ($credentials["country"] != null) {
            $credentials["country"] = Country::select("name")->find($credentials["country"])->name;
        }
        if ($credentials["state"] != null) {
            $credentials["state"] = State::select("name")->find($credentials["state"])->name;
        }
        if ($credentials["city"] != null) {
            $credentials["city"] = City::select("name")->find($credentials["city"])->name;
        }
        $user = User::create($credentials);
        if ($user) {
            Auth::attempt($credentials);
        }
        return response()->json(["success" => true]);
    }
    protected function login (Request $request) : JsonResponse
    {
        $credentials = $request->only("email", "password");
        $validator = Validator::make($credentials, [
            "email" => "required|email",
            "password" => "required",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $response = ["success" => false, "error" => $validator->errors()->first()];
        }
        if (Auth::attempt($credentials)) {
            $response = ["success" => true];
        } else {
            $response = ["success" => false, "error" => "Invalid email or password"];
        }
        return response()->json($response);
    }
    protected function logout () : JsonResponse
    {
        Auth::logout();
        return response()->json(["success" => true]);
    }
}
