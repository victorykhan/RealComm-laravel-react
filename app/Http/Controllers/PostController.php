<?php

namespace App\Http\Controllers;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use App\Events\PostCreated;

class PostController extends Controller
{
    //get all post

    public function index(Request $request, Post $post){

        $allPosts = $post->whereIn('user_id', $request->user()->following()->pluck('users.id')->push($request->user()->id))->with('user');

        $posts = $allPosts->orderBy('created_at','desc')->take(10)->get();
        return response()->json([
            'posts'=>$posts,
        ]);
    }



    public function create(Request $request, Post $post){
//create post
       $mypost = $request->user()->posts()->create([
            'body'=>$request->body,
        ]);

        //broadcast
        broadcast(new PostCreated($mypost, $request->user()))->toOther();
        //return the response in json format
        return response()->json($post->with('user')->find($mypost->id));








    }
}
