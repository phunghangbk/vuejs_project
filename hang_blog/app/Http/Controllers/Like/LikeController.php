<?php

namespace App\Http\Controllers\Like;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Like\Like;
use App\Model\Post\Post;
use Auth;
class LikeController extends Controller
{
    public function like(Request $request)
    {
        try { 
            $user = Auth::user();
            $postId = $request->postId;
            \Log::info($user);
            if (empty($postId) || empty($user)) {
                return response()->json([
                    'liked' => false
                ]);
            }

            if (empty(Post::where('post_id', $postId)->first())) {
                return response()->json([
                    'liked' => false
                ]);
            }
            Like::create([
                'user_id' => $user->user_id,
                'post_id' => $postId
            ]);
        } catch (\Exception $e) {
            Like::where('post_id', $postId)->where('user_id', $user->user_id)->delete();
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
        \Log::info($user);
        $postId = $request->postId;
        if (empty($user) || empty($postId)) {
            return response()->json([
                'liked' => false
            ]);
        }

        try {
            $result = Like::where('user_id', $user->user_id)->where('post_id', $postId)->first();
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
        if (empty($request->postId)) {
            return response()->json([
                'count' => 0
            ]);
        }

        try {
            $count = Like::where('post_id', $request->postId)->count();
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
