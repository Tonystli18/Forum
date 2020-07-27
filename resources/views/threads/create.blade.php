@extends('layouts.app')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="/threads">
                        @csrf
                        <div class="form-group">
                            <label for="channel_id">Choose a Channel</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose One...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{$channel->id}}" {{ old('channel_id') == $channel->id ? "selected" : "" }}>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" value="{{old('title')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <wysiwyg name="body" id="body"></wysiwyg>
                            {{-- <textarea name="body" id="body" cols="30" rows="8" class="form-control" required>{{old('body')}}</textarea> --}}
                        </div>

                        <div class="g-recaptcha" data-sitekey="6LcDbK4ZAAAAANqT5EyGFKTE18wrfaLKGYMbj2NW"></div>
                        <br/>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </div>

                        @if (count($errors))
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                    @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
