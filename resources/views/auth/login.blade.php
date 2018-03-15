@extends('layouts.static')

@section('title')
    <title>Login - BlogBits</title>
@endsection

@section('content')
    <div class="loginform">
        <form class="form" method="POST" action="{{ url('/login') }}">

            <h3>Login</h3>
            {!! csrf_field() !!}

            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="email">
                @if ($errors->has('email'))
                    <div class="errors">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="password">
                @if ($errors->has('password'))
                    <div class="errors">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-group">
                <div class="checkbox rememberMe">
                    <label>
                        <input type="checkbox" id="remember" name="remember"> Remember me
                    </label>
                </div>
                <button class="btn btn-default" type="submit">Log in</button>
            </div>

            <div class="form-group">
                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
            </div>

            <h4>Connect with social networks</h4>

            <div class="form-group">
                <a href="{{ url('/auth/facebook') }}">
                    <button class="btn btn-default" type="button"><i class="fa fa-btn fa-facebook"></i>Facebook</button>
                </a>

                <a href="{{ url('/auth/google') }}">
                    <button class="btn btn-default" type="button"><i class="fa fa-btn fa-google"></i>Google</button>
                </a>
            </div>


        </form>
    </div>
@endsection
