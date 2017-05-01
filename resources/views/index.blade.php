@extends('layouts.app')

@section('title')
    <title>Dashboard - BlogBits</title>
@endsection

@section('content')

    @include('sidebar')

    <div class="grid col-xs-12 col-sm-12 col-lg-4 col-md-4">
        @foreach($posts as $post)
            <div class="post">
                <div class="thumbnail">
                    <a class="edit-post" target="_blank" href="/content/edit/{{$post->id}}">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>

                    <img src="{{$post->uri}}">
                    <div class="caption">
                        <h3>{{substr($post->caption,0,30)}}</h3>
                        <button data-id="{{$post->id}}" class="btn btn-danger btn-sm pull-right delete-now">Delete</button>
                        <button data-id="{{$post->id}}" data-type="{{$post->type}}" class="btn btn-primary btn-sm pull-right post-now">Post Now</button>
                    </div>

                    <div class="actions">
                        <input type="text" class="form-control tags" data-id={{$post->id}} data-role="tagsinput" value="{{$post->tags}}" required>
                    </div>
                </div>
            </div>
        @endforeach

            @if($posts->count())
                {!! $posts->render() !!}
            @endif
    </div>
@endsection
