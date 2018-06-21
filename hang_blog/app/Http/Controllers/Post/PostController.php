<?php

namespace App\Http\Controllers\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Post\Post;

class PostController extends Controller
{
    const POST_PUBLISH = 1;
    public function create(Request $request)
    {
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
            $post->title = isset($request->title) ? $request->title : '';
            if ($request->image !== '' && $request->image !== null) {
                $image_name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                $image = \Image::make($request->image);
                $image->resize(720,720);
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
}
