<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function delete($id)
    {
        $comment = Comment::find($id);

        if (Gate::allows('comment-delete', $comment)) {
            $comment->delete();
            return back();
        } else {
            return back()->with('error', 'Unauthorize to delete this comment');
        }
    }

    public function add()
    {
        $comment = new Comment;

        $validator = validator(request()->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) return back();

        $comment->content = request()->content;
        $comment->article_id = request()->article_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        return back();
    }
}
