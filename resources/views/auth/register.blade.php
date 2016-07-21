@extends('layouts.app')

@section('title')
    <title>Registration - BlogBits</title>
@endsection

@section('content')
<div class="registerform">

    <form class="form"method="POST" action="{{ url('/register') }}">
        <h3>Sign up</h3>
        {!! csrf_field() !!}

        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="name">
            @if ($errors->has('name'))
                <div class="errors">{{ $errors->first('name') }}</div>
            @endif
        </div>

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

        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="confirm password">
            @if ($errors->has('password_confirmation'))
                <div class="errors">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="form-group">
            <button class="btn btn-default" type="submit">Register</button>
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
