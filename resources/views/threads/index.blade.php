@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            {{$threads->links()}}
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Search
                </div>
                <div class="card-body">
                    <form method="GET" action="/threads/a-search">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." name="q" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class=" btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(count($trending))
                <div class="card">
                    <div class="card-header">
                        Trending Threads
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($trending as $thread)
                            <a href="{{url($thread->path)}}">
                            <li class="list-group-item"> {{ $thread-> title}}</li>
                            </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
