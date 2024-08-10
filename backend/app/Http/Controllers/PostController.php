<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller 
{


     public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }



    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feilds = $request->validated([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        // $post = Post::create($feilds);

        $post = $request->user()->posts()->create($feilds);
        return $post;
    }

    /**
     * 
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
                return $post;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify',$post);

        $feilds = $request->validated([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);

        $post -> update($feilds);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify',$post);
         $post -> delete();

         return ['message' => "Post deleted successfully"];
    }
}