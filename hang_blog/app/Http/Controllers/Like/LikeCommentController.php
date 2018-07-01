<?php

namespace App\Http\Controllers\Like;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Post\Model\Post;
use Auth;
use App\Model\User;
use App\Model\Comment\Comment;
use App\Model\Like\LikeComment;

class LikeCommentController extends Controller
{
     public function like(Request $request)
    {
        try { 
            $user = Auth::user();
            $commentId = $request->commentId;
            if (empty($commentId) || empty($user)) {
                return response()->json([
                    'liked' => false
                ]);
            }

            if (empty(Comment::where('comment_id', $commentId)->first())) {
                return response()->json([
                    'liked' => false
                ]);
            }
            LikeComment::create([
                'user_id' => $user->user_id,
                'comment_id' => $commentId
            ]);
        } catch (\Exception $e) {
            LikeComment::where('comment_id', $commentId)->where('user_id', $user->user_id)->delete();
            return response()->json([
                'liked' => false
            ]);
        }
        return response()->json([
            'liked' => true
        ]);
    }

    public function isLiked(Request $request) {
        $user = Auth::user();
        $commentId = $request->commentId;

        if (empty($user) || empty($commentId)) {
            return response()->json([
                'liked' => false
            ]);
        }

        try {
            $result = LikeComment::where('user_id', $user->user_id)->where('comment_id', $commentId)->first();
            if (! empty($result)) {
                return response()->json([
                    'liked' => true
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'liked' => false
            ]);
        }
    }

    public function likesCount(Request $request)
    {
        if (empty($request->commentId)) {
            return response()->json([
                'count' => 0
            ]);
        }

        try {
            $count = LikeComment::where('comment_id', $request->commentId)->count();
            return response()->json([
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0
            ]);
        }
    }
}
