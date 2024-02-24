<?php

namespace App\Http\Controllers\General;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\CommentStoreRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @param CommentStoreRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function create(CommentStoreRequest $request, $id)
    {
        $model = $request->input('model');
        $user = auth()->user();
        $commentValue = $request->input('comment');

        $comment = new Comment();
        $comment->setAttribute('comment', $commentValue);
        $comment->setAttribute('user_id', $user->id);
        $comment->setAttribute('parent_id', request()->input('parent_id'));
        $comment->setAttribute('commentable_id', $id);
        $comment->setAttribute('commentable_type', $model);
        $comment->save();

        if (request()->ajax()) {
            return response()->json(['return' => true]);
        }
        alert()->success('Comment added successfully!', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $commentId = $request->input('commentId');
        $commentValue = $request->input('commentValue');
        $comment = Comment::where('id', $commentId)->first();
        $comment->comment = $commentValue;
        $comment->save();
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $commentId = $request->input('commentId');
        Comment::where('parent_id', $commentId)->delete();
        Comment::where('id', $commentId)->delete();
    }

    /**
     * @param $model
     * @param $modelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments($model, $modelId)
    {
        $model = app($model)->find($modelId);
        $comments = $model->comments()->get();
        $comments = $comments->map(function ($comment) {
            if ($comment->created_at->diffInSeconds(carbon()->now()) < 40) {
                $diff = 'Just Now';
            } else {
                $diff = carbon()->now()->sub($comment->created_at->diff(carbon()->now()))->diffForHumans();
            }
            $comment->created = $diff;
            return $comment;
        });
        return response()->json($comments->toArray());
    }
}
