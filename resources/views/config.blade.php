@extends('layouts.app')

@section('title')
    <title>Config - BlogBits</title>
@endsection

@section('content')
    <div class="container config">
        <form action="/config" method="POST">
            <div class="form-group">
                <label>Active Blog</label>
                <input type="text" class="form-control" name="active_blog" placeholder="Active Tumblr blog" value="{{$config['active_blog']}}">
            </div>
            <div class="form-group">
                <label>Post Link</label>
                <input type="text" class="form-control" name="post_link" placeholder="Post Photo link" value="{{$config['post_link']}}">
            </div>
            <div class="form-group">
                <label>Pinterest</label>
                <input type="text" class="form-control" name="pinterest" placeholder="Pinterest Profile" value="{{$config['pinterest']}}">
            </div>
            <div class="form-group">
                <label>Facebook</label>
                <input type="text" class="form-control" name="facebook" placeholder="Facebook Page" value="{{$config['facebook']}}">
            </div>
            <div class="form-group">
                <label>Sync Folder</label>
                <input type="text" class="form-control" name="sync_folder" placeholder="Sync Folder (File System)" value="{{$config['sync_folder']}}">
            </div>
            <div class="form-group">
                <label>Default Tags</label>
                <input type="text" class="form-control" name="default_tags" placeholder="Default Tags (separated by commas)" value="{{$config['default_tags']}}">
            </div>

            <div class="form-group">
                <label>Batch Posts Limit</label>
                <input type="text" class="form-control" name="batch_post_limit" placeholder="Batch Post limit (default 5)" value="{{$config['batch_post_limit']}}">
            </div>

            <div class="form-group">
                @if(Session::has('updated'))
                    <div class="alert alert-success" role="alert">{{Session::get('updated')}}</div>

                @endif
            </div>
            {{csrf_field()}}
            <button type="submit" class="btn btn-default pull-right">Update</button>
        </form>
    </div>
@endsection