<?php

namespace App\Http\Controllers\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\user;
use App\Post\Post;
use App\Comment\Comment;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $postId = $request->postId;
        $user = Auth::user();
        try {
            if (empty($user) || empty($postId)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.cannot_create_comment')],
                    'comment' => null
                ]);
            }

            $content = $request->content;
            if (empty($content)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.input_comment_content')],
                    'comment' => null
                ]);
            }

            if (empty(Post::where('post_id', $postId)->first())) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.cannot_create_comment')],
                    'comment' => null
                ]);
            }

            $parentId = ! empty($request->parentId) ? $request->parentId : null;
            $comment = Comment::create([
                'user_id' => $user->user_id,
                'post_id' => $postId,
                'content' => $content,
                'parent_id' => $parent_id
            ]); 
        } catch (\Exception $e) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()],
                'comment' => null
            ]);
        }

        return response()->json([
            'status' => config('application.response_status')['success'],
            'errors' => [],
            'comment' => $comment
        ]);
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            if (empty($user)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.comment_permission')],
                ]);
            }
            $commentId = $request->commentId;
            if (empty($commentId)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.cannot_update_comment')],
                ]);
            }
            $comment = Comment::where('user_id', $user->user_id)->where('comment_id', $commentId)->first();
            if (empty($comment)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.cannot_update_comment')],
                ]);
            }

            $content = $request->content;
            if (empty($content)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.cannot_update_comment')],
                ]);
            }
            $comment->content = $content;
            $content->save();
        } catch (\Exception $e) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()],
            ]);
        }

        return response()->json([
            'status' => config('application.response_status')['success'],
            'errors' => [],
            'comment' => $comment
        ]);
    }

    public function delete(Request $request)
    {
        try {
            $authUser = Auth::user();

            if (empty($authUser)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.comment_permission')],
                ]);
            }

            $commentId = $request->commentId;
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => config('application.cannot_delete_comment')],
            ]);

            $comment = Comment::where('comment_id', $commentId)->first();
            $createCmtUser = $comment->user();
            if ($createUser->user_id == $authUser->user_id) {
                $comment->delete();
            } else {
                $post = $comment->post();
                $createPostUser = $post->user();
                if ($createPostUser->user_id == $authUser->user_id) {
                    $comment->delete();
                } else {
                    return return response()->json([
                        'status' => config('application.response_status')['error'],
                        'errors' => ['error' => config('application.cannot_delete_comment')],
                    ]);
                }
            }

            return response()->json([
                'status' => config('application.response_status')['success'],
                'errors' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function listByPost(Request $request)
    {
        $post = Post::where('post_id', $request->postId)->first();
        $comments = [];
        if (! empty($post)) {
            $comments = $post->comments();
        }

        return response()->json([
            'comments' => $comments
        ]);
    }

    public function listByUser(Request $request)
    {
        $user = User::where('user_id', $userId)->first();
        $comments = [];
        if (! empty($user)) {
            $comments = $user->comments();
        }

        return response()->json([
            'comments' => $comments
        ]);
    }

    public function comments($commentId)
    {
        return response()->json([
            'comment' => Comment::where('comment_id', $commentId)->first()
        ]);
    }

    public function findChildComment($parentId) 
    {
        return response()->json([
            'comments' => Comment::where('parent_id', $parentId)->get()
        ]);
    }
}
