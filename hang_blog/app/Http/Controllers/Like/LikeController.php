<?php

namespace App\Http\Controllers\Like;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Like\Like;
use App\Post\Post;
use Auth;
class LikeController extends Controller
{
    public function like(Request $request)
    {
        try { 
            $user = Auth::user();
            $postId = $request->postId;
            if (empty($postId) || empty($user)) {
                \Log::info(1);
                return response()->json([
                    'liked' => false
                ]);
            }

            if (empty(Post::where('post_id', $postId)->first())) {
                \Log::info(2);
                return response()->json([
                    'liked' => false
                ]);
            }
            Like::create([
                'user_id' => $user->user_id,
                'post_id' => $postId
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
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
        $postId = $request->postId;

        if (empty($user) || empty($postId)) {
            \Log::info(1);
            return response()->json([
                'liked' => false
            ]);
        }

        try {
            $result = Like::where('user_id', $user->user_id)->where('post_id', $postId)->first();
            if (! empty($result)) {
                \Log::info(2);
                return response()->json([
                    'liked' => true
                ]);
            }
        } catch (\Exception $e) {
            \Log::info(3);
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
