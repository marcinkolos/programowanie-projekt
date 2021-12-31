<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
                return response('Visibility must be set', 422);
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
            return $post;
        else 
            return response('You don\'t have access to this post', 403);
    }

    public function getPosts(){
        $userId = Auth::id();
        $publicPosts = Post::where('isPrivate', false);
        $privatePosts = Post::where([
            ['isPrivate', true], ['sender', $userId]
        ])->orWhere([
            ['isPrivate', true], ['receiver', $userId]
        ]);

        $allPosts = $publicPosts
            ->union($privatePosts)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return $allPosts;
    }

    public function deletePost($id){
        $userId = Auth::id();
        $post = Post::findOrFail($id);
        if($post->sender == $userId) 
            return $post->delete();
        else 
            return response('You don\'t have rights to delete this post', 403);
    }

    public function patchPost(Request $request, $id){
        $userId = Auth::id();
        $post = Post::findOrFail($id);
        $title = $request->input('title');
        $message = $request->input('message');
        if($post->sender == $userId){
            $post->update([
                'title' => $title ? $title : $post->title,
                'message' => $message ? $message : $post->message
            ]);
            return $post;
        } 
        else 
            return response('You don\'t have rights to edit this post', 403);
    }
}