@forelse ($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @auth
                            @if ($thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                            @endauth

                            @guest
                                {{ $thread->title }}
                            @endguest                            
                        </a>
                    </h4>
                    <h6>
                        Posted By: <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a>
                    </h6>
                </div>
                <a href="{{ $thread->path() }}">
                    {{$thread->replies_count}} {{ Str::plural('reply', $thread->replies_count)}}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="body">{{ $thread->body }}</div>
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse