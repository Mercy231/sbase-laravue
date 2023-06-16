<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
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
    protected function getUsers () : JsonResponse
    {
        return response()->json(User::all());
    }
    protected function updateUserdata ($id, Request $request) : JsonResponse
    {
        $user = User::find($id)->toArray();
        $user["country"] = (array) json_decode($user["country"]);
        $user["state"] = (array) json_decode($user["state"]);
        $user["city"] = (array) json_decode($user["city"]);
        $credentials = [];
        if (!empty($request->userdata)) {
            foreach ($request->userdata as $key => $value) {
                if ($key == "image") continue;
                if ($request->userdata[$key] != $user[$key]) {
                    $credentials[$key] = $value;
                }
            }
        }
        if($request->hasFile('image')) $credentials["image"] = $request->file('image');
        $validator = Validator::make($credentials, [
            "fullName" => "min:2",
            "email" => "email|unique:users,email",
            "balance" => "numeric",
            "image" => "image",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        unset($credentials["image"]);

        User::where("id", $id)->update($credentials);

        if($request->hasFile('image')){
            $request->image = $request->file('image');
            $filename = md5($request->image->getClientOriginalName().time()).'.'.$request->image->extension();
            $request->image->storeAs('public/images/avatars/', $filename);
            User::where("id", $id)->update(["avatar" => $filename]);
        }
        return response()->json(true);
    }
    protected function destroy ($id) : JsonResponse
    {
        User::find($id)->delete();
        return response()->json(true);
    }
    protected function getStatistics ($id, Request $request) : JsonResponse
    {
        if (!empty($request->dateFrom) && !empty($request->dateTo)) {
            $from = Carbon::createFromDate($request->dateFrom);
            $to = Carbon::createFromDate($request->dateTo);
        } else {
            return response()->json("Date range required.");
        }
        $range = [];
        $count = [];
        while ($from <= $to) {
            $range[] = Carbon::createFromDate($from)->format('Y-m-d');
            $count["posts"][] = count(Post::where("user_id", $id)
                ->whereBetween("created_at", [
                    $from,
                    Carbon::createFromDate($from)->endOfDay()
                ])
                ->get());
            $count["comments"][] = count(Comment::where("user_id", $id)
            ->whereBetween("created_at", [
                $from,
                Carbon::createFromDate($from)->endOfDay()
            ])
                ->get());
            $from->next("day");
        }
        return response()->json(["range" => $range, "count" => $count]);
    }
}
