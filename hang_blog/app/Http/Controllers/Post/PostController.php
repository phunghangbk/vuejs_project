<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Post\Post;
use App\Post\UserPost;
use App\User;
use Auth;


class PostController extends Controller
{
    const POST_PUBLISH = 1;
    const IS_POSTED_USER = 1;
    const PER_PAGE = 4;
    public function create(Request $request)
    {
        $user = Auth::user();
        if (empty($user)) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => config('application.permission')],
            ]);
        }
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => $validator->errors()
            ]);
        }

        try {
            $post = new Post;
            $post->user_id = $user->user_id;
            $post->title = isset($request->title) ? $request->title : '';
            if ($request->image !== '' && $request->image !== null) {
                $image_name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                $image = \Image::make($request->image);
                $image->resize(350,500);
                $image->save(public_path('post/images/') . $image_name);
                $post->image = $image_name;
            }

            $post->content = isset($request->content) ? $request->content : '';
            $post->status = isset($request->status) ? $request->status : self::POST_PUBLISH;
            $post->introduction = isset($request->introduction) ? $request->introduction : '';
            $post->save();

            return response()->json([
                'status' => config('application.response_status')['success'],
                'errors' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function getPost($post_id, Request $request)
    {
        try {
            $user = User::with(['posts' => function ($query) use ($post_id) {
                $query->where('post_id', $post_id);
            }])->where('nickname', $request->nickname)->first();
            $post = $user->posts;
            if (empty($post)) {
                return response()->json([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['post_id' => config('application.has_no_post')],
                    'post' => null
                ]);
            }

            return response()->json([
                'status' => config('application.response_status')['success'],
                'post' => $post,
                'errors' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'post' => null,
                'errors' => ['error' => $e->getMessage()]
            ]);
        }
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => $validator->errors(),
                'list' => null
            ]);
        }
        $authUser = Auth::user();
        $user = User::where('nickname', $request->nickname)->first();
        if (empty($user)) {
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => config('application.has_no_user')],
                'list' => null
            ]);
        }
        if (! empty($authUser) && $authUser->user_id == $user->user_id) {
            $list = Post::where('user_id', $user->user_id)->paginate(self::PER_PAGE);
        } else {
            $list = Post::where('user_id', $user->user_id)->where('status', self::POST_PUBLISH)->paginate(self::PER_PAGE);
        }
        \Log::info(json_encode($list));
        return response()->json([
            'status' => config('application.response_status')['success'],
            'errors' => [],
            'list' => $list
        ]);
    }
}
