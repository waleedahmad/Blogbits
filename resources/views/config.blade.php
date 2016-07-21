@extends('layouts.app')

@section('title')
    <title>Config - BlogBits</title>
@endsection

@section('content')

    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#blog" aria-controls="blog" role="tab" data-toggle="tab">Blog</a></li>
            <li role="presentation"><a href="#scheduler" aria-controls="scheduler" role="tab" data-toggle="tab">Scheduler</a></li>
            <li role="presentation"><a href="#account" aria-controls="account" role="tab" data-toggle="tab">Account</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            {{--Blog Settings--}}
            <div role="tabpanel" class="tab-pane active" id="blog">
                <div class="config">
                    <form action="/config/blog" method="POST">
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
                            @if(Session::has('blog_flash'))
                                <div class="alert alert-success" role="alert">{{Session::get('blog_flash')}}</div>
                            @endif
                        </div>
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                </div>
            </div>

            {{--Scheduler Settings--}}
            <div role="tabpanel" class="tab-pane" id="scheduler">
                <div class="config">
                    <form action="/config/scheduler" method="POST">

                        <div class="form-group">
                            <?php
                                $frequencies = [
                                    'everyMinute',
                                    'everyFiveMinutes',
                                    'everyTenMinutes',
                                    'everyThirtyMinutes',
                                    'hourly'
                                ];
                            ?>
                            <label>Scheduler Frequency</label>
                            <select class="form-control" name="scheduler_frequency" value="{{$config['scheduler_frequency']}}">

                                @foreach($frequencies as $frequency)
                                    @if($config['scheduler_frequency'] === $frequency)
                                        <option value="{{$frequency}}" selected="selected">{{ucfirst(join(preg_split('/(?<=[a-z])(?=[A-Z])/x', $frequency), " " ))}}</option>
                                    @else
                                        <option value="{{$frequency}}">{{ucfirst(join(preg_split('/(?<=[a-z])(?=[A-Z])/x', $frequency), " " ))}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Batch Posts Limit</label>
                            <input type="text" class="form-control" name="batch_post_limit" placeholder="Batch Post limit (default 5)" value="{{$config['batch_post_limit']}}">
                        </div>

                        <div class="form-group">
                            @if(Session::has('scheduler_flash'))
                                <div class="alert alert-success" role="alert">{{Session::get('scheduler_flash')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Update</button>
                        </div>


                        <div class="form-group">
                            <div class="form-inline">
                                <h3>Scheduler Timings</h3>
                                <label>Start</label>
                                <input type="text" name="scheduler_start_time" value={{$config['scheduler_start_time']}} id="scheduler_start_time" class="form-control" />
                                <label>End</label>
                                <input type="text" name="scheduler_end_time" value={{$config['scheduler_end_time']}}  id="scheduler_end_time" class="form-control" />
                                <a class="btn btn-default" href="#" id="up-sch-timings" role="button">Update Timings</a>
                            </div>
                        </div>

                        {{csrf_field()}}
                    </form>
                </div>
            </div>

            {{--Account Settings--}}
            <div role="tabpanel" class="tab-pane" id="account">
                <div class="config">
                    <form action="/config/user" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$config['user_email']}}" placeholder="email">
                            @if ($errors->has('email'))
                                <div class="errors">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="password">
                            @if ($errors->has('password'))
                                <div class="errors">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            @if(Session::has('account_flash'))
                                <div class="alert alert-success" role="alert">{{Session::get('account_flash')}}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-default">Update</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection