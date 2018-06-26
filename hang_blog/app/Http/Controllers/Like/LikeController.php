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
            $userId = Auth::user();
            $postId = $request->postId;
            if (empty($postId) || empty($userId)) {
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
                'user_id' => $userId,
                'post_id' => $postId
            ]);
        catch (\Exception $e) {
            Like::where('post_id', $postId)->where('user_id', $userId)->delete();
            return response()->json([
                'liked' => false
            ]);
        }

        return response()->json([
            'liked' => true
        ]);
    }

    public function isLiked(Request $request) {
        $userId = Auth::user();
        $postId = $request->postId;

        if (empty($userId) || empty($postId)) {
            return response()->json([
                'liked' => false
            ]);
        }

        try {
            $result = Like::where('user_id', $userId)->where('post_id', $postId)->first();
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
