<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        $threads = Thread::search(request('query'))->paginate(25);

        if(request()->expectsJson()) {
            return $threads;
        };

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function algoliaSearch(Trending $trending)
    {
        return view('threads.search', [
            'trending' => $trending->get()
        ]);
    }
}
