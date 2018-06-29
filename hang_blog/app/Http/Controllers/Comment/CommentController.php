<?php

namespace App\Http\Controllers\Comment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\user;
use App\Post\Post;
use App\Comment\Comment;
use Auth;

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
                'parent_id' => $parentId
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

    public function update($commentId, Request $request)
    {
        try {
            $user = Auth::user();
            if (empty($user)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.comment_permission')],
                ]);
            }
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
            $comment->save();
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

    public function delete($commentId)
    {
        try {
            $authUser = Auth::user();

            if (empty($authUser)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.comment_permission')],
                ]);
            }

            $authUser = Auth::user();
            $comment = Comment::where('user_id', $authUser->user_id)->where('comment_id', $request->commentId)->first();
            if (! empty($comment)) {
                $comment->delete();
            } else {
                $comment = Comment::where('comment_id', $request->commentId)->first();

                if (! empty(Post::where('user_id', $authUser->user_id)->where('post_id', $comment->post_id)->first())) {
                    $comment->delete(); 
                } else {
                    return response()->json([
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
        $post = Post::with(['comments' => function ($query) {
            $query->with('user')->whereNull('parent_id');
        }])->find($request->postId);

        return response()->json([
            'comments' => $post->comments
        ]);
    }

    public function findByParent(Request $request) 
    {
        $comments = Comment::with('user')->where('parent_id', $request->parentId)->get();
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

    public function countReply(Request $request)
    {
        $count = Comment::where('parent_id', $request->parentId)->count();
        return response()->json([
            'count' => $count
        ]);
    }

    public function postCommentsCount(Request $request)
    {
        if (empty($request->postId)) {
            return response()->json([
                'count' => 0
            ]);
        }

        $count = Comment::where('post_id', $request->postId)->count();
        return response()->json([
            'count' => $count
        ]);
    }
    public function canDeleteComment(Request $request)
    {
        $authUser = Auth::user();
        if (! empty(Comment::where('user_id', $authUser->user_id)->where('comment_id', $request->commentId)->first())) {
            return response()->json([
                'canDeleteComment' => true
            ]);
        }

        $comment = Comment::with(['post' => function ($query) use ($authUser) {
            $query->where('user_id', $authUser->user_id);
        }])->find('comment_id', $request->commentId);    
        if (! empty($comment->post)) {
            return response()->json([
                'canDeleteComment' => true
            ]); 
        }

        return response()->json([
            'canDeleteComment' => false
        ]); 
    }
    public function canUpdateComment(Request $request)
    {
        $authUser = Auth::user();
        if (! empty(Comment::where('user_id', $authUser->user_id)->where('comment_id', $request->commentId)->first())) {
            return response()->json([
                'canUpdateComment' => true
            ]);
        }
        return response()->json([
            'canUpdateComment' => false
        ]);
    }
}
