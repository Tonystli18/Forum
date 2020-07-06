<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use App\Trending;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

class ThreadController extends Controller
{
    /*
    *  Create a new ThreadController instance
    */

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        // if($channel->exists) {
        //     $threads = $channel->threads()->latest();
        // } else {
        //     $threads = Thread::latest();
        // }

        // if($username = request('by')) {
        //     $user = User::where('name', $username)->firstOrFail();
        //     $threads = $threads->where('user_id', $user->id);
        // }

        // $threads = $threads->filter($filters)->get();

        if(request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required | spamfree',
            'body' => 'required | spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        if(request()->wantsJson()) {
            return response($thread, 201);
        };

        return redirect($thread->path())
                ->with('flash', 'Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if(auth()->check()) {
            auth()->user()->read($thread);
        };

        $trending->push($thread);

        $thread->increment('visits');

        /**
         * record visits feature implemented base on Redis
         */
        // $thread->visits()->record();

        // return Thread::withCount('replies')->find(55);
        // return $thread->getRepliesCount();

        // return view('threads.show', [
        //     'thread' => $thread,
        //     'replies' => $thread->replies()->paginate(20)
        // ]);

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // another way to keep database consistancy
        // $thread->replies()->delete();

        $this->authorize('update', $thread);

        // if($thread->user_id != auth()->id()) {
        //     abort(403, 'You do not have permission to do this');    
        //     // if(request()->wantsJson()) {
        //     //     return response(['status' => 'Permission Denied'], 403);
        //     // }

        //     // return redirect('/login');
        // }

        $thread->delete();

        if(request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads->paginate(25);
    }
}
