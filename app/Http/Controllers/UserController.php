<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected function getUser () : JsonResponse
    {
        if (Auth::user()) {
            return response()->json(["user" => Auth::user()]);
        } else {
            return response()->json(["error" => "No such user"]);
        }
    }
}
