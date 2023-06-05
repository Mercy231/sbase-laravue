<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    protected function changeAvatar (Request $request) : JsonResponse
    {
        $validator = Validator::make($request->only('image'), [
            "image" => "required|image"
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = md5($file->getClientOriginalName().time()).'.'.$file->extension();
            $file->storeAs('public/images/avatars/', $filename);
            User::where("id", Auth::user()->id)->update(["avatar" => $filename]);
        } else {
            return response()->json("Image is required!");
        }
        return response()->json(true);
    }
    protected function changeUserdata (Request $request) : JsonResponse
    {
        $validator = Validator::make($request->only("fullName", "email"), [
            "fullName" => "required|min:2",
            "email" => "required|email|unique:users,email",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        User::where("id", Auth::user()->id)
            ->update([
                "full_name" => $request->fullName,
                "email" => $request->email,
                "email_verified_at" => null,
            ]);
        return response()->json(true);
    }
}
