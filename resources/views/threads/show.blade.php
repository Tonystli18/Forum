@extends('layouts.app')

@section('content')
<thread-view :thread="{{$thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._question')
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
                        <div class="flex level">
                            <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}" v-if="signedIn"></subscribe-button>
                            <button class="button-blue ml-2" 
                                    v-if="authorize('isAdmin')" 
                                    @click="toggleLock" 
                                    v-text="locked ? 'Unlock' : 'Lock'"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>

@endsection
