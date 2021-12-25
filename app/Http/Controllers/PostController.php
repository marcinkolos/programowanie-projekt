<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    public function addPost(Request $request)
    {   
        $isPrivate = $request->input('isPrivate');
        
        $validator = Validator::make($request->all(), [
            'isPrivate' => 'required',
            'title' => 'required|filled',
            'message' => 'required|filled',
            'receiver' => $isPrivate ? 'required|exists:users,id' : 'nullable',
        ]);

        if ($validator->fails()) {
            $validationErrors = $validator->errors();
            if ($validationErrors->has('isPrivate')) {
                return response(
                    'Visibility must be set'
                    , 422);
            }

            return $validator->errors();
        }

        $post = new Post;
        $post->isPrivate = $isPrivate;
        $post->title = $request->input('title');
        $post->message = $request->input('message');
        $post->sender = Auth::id();
        $post->receiver = $isPrivate ? $request->input('receiver') : null;
        $post->save();
        return $post;
    }

    public function getPost($id){
        $post = Post::findOrFail($id);

        if(!$post->isPrivate || $post->sender == Auth::id() || $post->receiver == Auth::id())
        {
            return $post;
        }
        else
        {
            return response(
                'You don\'t have access to this post'
                , 403);
        }
    }
}
