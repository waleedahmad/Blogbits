@extends('layouts.app')

@section('title')
    <title>Edit Post - BlogBits</title>
@endsection

@section('content')
    <div class="grid">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4 col-md-4 col-lg-4 post">
                <div class="thumbnail">
                    <a class="edit-post" href="/content/edit/{{$post->id}}">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>

                    <img src="{{$post->uri}}">
                    <div class="caption">
                        <form action="/content/update" method="POST">
                            <input type="text" class="form-control image-caption" name="caption" placeholder="Caption" value="{{$post->caption}}" required>
                            <input type="hidden" name="id" value="{{$post->id}}">
                            <button data-id="{{$post->id}}" data-type="{{$post->type}}" class="btn btn-primary btn-sm pull-right update-post">Update</button>
                            {{csrf_field()}}
                        </form>
                    </div>

                    <div class="actions">
                        <input type="text" class="form-control tags" data-id={{$post->id}} data-role="tagsinput" value="{{$post->tags}}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
