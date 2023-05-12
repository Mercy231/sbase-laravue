<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    protected function create (Request $request) : JsonResponse
    {
        $fields = $request->only("text");
        $validator = Validator::make($fields, [
            "text" => "required|min:1",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        if ($request->comment_id) {
            $comment = Comment::create([
                "user_id" => Auth::user()->id,
                "post_id" => null,
                "comment_id" => $request->comment_id,
                "text" => $request->text,
            ]);
        } else {
            $comment = Comment::create([
                "user_id" => Auth::user()->id,
                "post_id" => $request->post_id,
                "comment_id" => null,
                "text" => $request->text,
            ]);
        }
        return response()->json($comment);
    }
    protected function update ($id, Request $request) : JsonResponse
    {
        $fields = $request->only("text");
        $validator = Validator::make($fields, [
            "text" => "required|min:1",
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json($validator->errors()->first());
        }
        $comment = Comment::find($id)->update([
            "text" => $request->text,
        ]);
        return response()->json($comment);
    }
    protected function destroy ($id) : JsonResponse
    {
        $comment = Comment::find($id);
        if (Auth::user()->id == $comment->user_id) {
            $comment->delete();
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
