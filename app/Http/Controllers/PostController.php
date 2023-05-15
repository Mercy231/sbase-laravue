<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected function getPosts () : JsonResponse
    {
        return response()->json(Post::latest()->get());
    }
    protected function create (Request $request) : JsonResponse
    {
        $fields = $request->only("title", "text");
        $validator = Validator::make($fields, [
            "title" => "required|min:3",
            "text" => "required|min:1",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        $post = Post::create([
            "user_id" => Auth::user()->id,
            "title" => $request->title,
            "text" => $request->text,
        ]);
        return response()->json($post);
    }
    protected function update ($id, Request $request) : JsonResponse
    {
        $fields = $request->only("title", "text");
        $validator = Validator::make($fields, [
            "title" => "required|min:3",
            "text" => "required|min:1",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        $post = Post::find($id)->update([
            "title" => $request->title,
            "text" => $request->text,
        ]);
        return response()->json($post);
    }
    protected function destroy ($id) : JsonResponse
    {
        $post = Post::find($id);
        if (Auth::user()->id == $post->user_id) {
            $post->delete();
            return response()->json(true);
        } else {
            return response()->json("Invalid post");
        }
    }
}
