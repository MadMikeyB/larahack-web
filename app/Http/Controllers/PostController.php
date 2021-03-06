<?php

namespace App\Http\Controllers;

use App\Helpers\Stage;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Stage::getEvent();

        if ($event) {
            $posts = Post::where('event_id', $event->id)->orderBy('id', 'desc');
        } else {
            $posts = Post::orderBy('id', 'desc');
        }

        $posts = $posts->paginate(10);

        return view('post.index', ['posts' => $posts, 'event' => $event]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $latest_post = Post::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        return view('post.create', ['latest_post' => $latest_post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'title' => 'required|max:80',
           'description' => 'required'
        ]);


        $latest_post = Post::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();

        if ($latest_post && $latest_post->created_at > \Carbon\Carbon::now()->addMinutes(-30)) {
            return redirect(url('posts'));
        }

        $event = Stage::getEvent();

        Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => Auth::user()->id,
            'event_id' => $event->id
        ]);



        return redirect(url('posts'));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
