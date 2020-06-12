@component ('profiles.activities.activity_template')
    @slot('heading')
    {{$profileUser->name}} published 
    <a href="{{ $activity->subject->path() }}">"{{$activity->subject->title}}"</a>
    @endslot
    @slot('body')
    {{ $activity->subject->body }}
    @endslot
@endcomponent
