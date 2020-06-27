@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class=" page-header">
                <avatar-form :user="{{ $profileUser }}"></avatar-form>
                <hr>
            </div>
            <div>
                @forelse ($activities as $date => $activityGroup)
                    <h3 class="page-header">{{ $date }}</h3> <hr>
                    @foreach ($activityGroup as $activity)
                        @if (view()->exists("profiles.activities.$activity->type"))
                            @include("profiles.activities.$activity->type")
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet</p>
                @endforelse
            </div>
        </div>
    </div>
    
</div>
@endsection