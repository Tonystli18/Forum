{{-- if editing show this --}}
<div class="card mb-4" v-if="editing">
    <div class="card-header">
        <div class="level">
            <input type="text" class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="form-group">
            <textarea name="body" id="body" class=" form-control" rows="10" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button @click="update" class=" btn-primary level-item">Update</button>
            <button @click="resetForm">Cancel</button>
    
            @can('update', $thread)
            <form action=" {{ $thread->path() }}" method="post" class="ml-auto">
                @csrf
                {{ method_field('DELETE')}}
                <button type="submit" class="btn btn-link"> Delete Thread</button>
            </form>
            @endcan
        </div>
    </div>
</div>

{{-- if not editing, show question --}}
<div class="card mb-4" v-else>
    <div class="card-header">
        <div class="level">
            <img src="{{ asset($thread->creator->avatar_path) }}" 
                width="25" height="25" class="mr-1">
            <span class="flex">
                <a href="{{route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:
                <span v-text="title"></span>
            </span>
        </div>
    </div>

    <div class="card-body" v-text="body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button @click="editing = true" class="btn-primary">Edit</button>
    </div>
</div>