@extends('layouts.app')

@section('content')
<thread-view :thread="{{$thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._question')
                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
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
