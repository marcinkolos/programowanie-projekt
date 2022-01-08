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
        $sender = User::findOrFail($post->sender)->makeHidden(['id', 'username', 'email', 'created_at', 'updated_at']);
        $receiver = User::find($post->receiver)->makeHidden(['id', 'username', 'email', 'created_at', 'updated_at']);
        if(!$post->isPrivate || $post->sender == Auth::id() || $post->receiver == Auth::id()) {
            $post->sender = $sender;
            $post->receiver = $receiver ? $receiver : null;
            return $post;
        } 
        else 
            return response('You don\'t have access to this post', 403);
    }

    public function getPosts(Request $request){
        $userId = Auth::id();
        $publicPosts = Post::where('isPrivate', false)->orderBy('created_at', 'desc')->get();
        $sentPosts = Post::where('sender', $userId)->orderBy('created_at', 'desc')->get();
        $privatePosts = Post::where([
            ['isPrivate', true], ['receiver', $userId]
        ])->orderBy('created_at', 'desc')->get();

        if($request->query('sent')) {
            return $sentPosts;
        } else if ($request->query('privateonly')) {
            return $privatePosts;
        }
        return $publicPosts;
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