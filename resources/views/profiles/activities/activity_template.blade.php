<div class="card mb-4">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                {{$heading}}
            </span>
        </div>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        {{ $body }}
    </div>
</div>