@extends('layouts.static')

@section('title')
    <title>Registration - BlogBits</title>
@endsection

@section('content')
<div class="registerform">

    <form class="form"method="POST" action="{{ url('/register') }}">
        <h4 class="text-center">Connect with Facebook</h4>

        <div class="form-group text-center">
            <a href="{{ url('/auth/facebook') }}">
                <button class="btn btn-default fb-btn" type="button"><i class="fa fa-btn fa-facebook"></i>Facebook</button>
            </a>
        </div>


    </form>
</div>
@endsection
