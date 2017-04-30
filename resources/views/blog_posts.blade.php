@extends('layouts.app')

@section('title')
    <title>Dashboard - BlogBits</title>
@endsection

@section('content')

    <div class="grid">
        @foreach($blog->posts as $post)

            @foreach($post->photos as $photo)
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-4 col-lg-4 post">
                        <div class="thumbnail">
                            <a class="edit-post" target="_blank" href="/content/edit/">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </a>

                            <img src="{{$photo->original_size->url}}">
                            <div class="caption">
                                <h3>{!! $post->caption !!}</h3>
                                <button data-id="" class="btn btn-danger btn-sm pull-right delete-now">Delete</button>
                                <button data-id="" data-type="" class="btn btn-primary btn-sm pull-right post-now">Post Now</button>
                            </div>

                            <div class="actions">
                                <input type="text" class="form-control tags" data-id= data-role="tagsinput" value="{{implode (", ", $post->tags)}}" required>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        <ul class="pager">
            <li>
                <a href="http://127.0.0.1:8080/tumblr/?offset={{$offset}}" rel="next">>></a>
            </li>
        </ul>
    </div>
@endsection
