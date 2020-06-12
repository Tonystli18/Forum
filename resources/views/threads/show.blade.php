@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{$thread->replies_count}}"inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                            <form action=" {{ $thread->path() }}" method="post">
                                @csrf
                                {{ method_field('DELETE')}}
                                <button type="submit" class="btn btn-link"> Delete Thread</button>
                            </form>
                            @endcan
                        </div>
                    </div>
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{ $thread->body }}
                    </div>
                </div>
    
                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
    
                {{-- @auth
                <form method="POST" action="{{ $thread->path() . '/replies' }}">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" id="body" cols="100%" rows="5" placeholder="Have something to say?"></textarea>
                    </div>
                    <button type="submit">Post</button>
                </form>
                @endauth --}}
    
                {{-- @guest
                <p class="text-center">Please <a href="{{ route('login')}}">sign in</a> to participate in this discussion</p>
                @endguest --}}
    
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by 
                            <a href="#"> {{ $thread->creator->name }} </a>, and currently has 
                            <span v-text="repliesCount"></span>
                            {{ Str::plural('comment', $thread->replies_count)}}. 
                        </p>
                        <p>
                            @auth
                            <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                            @endauth
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>

@endsection
