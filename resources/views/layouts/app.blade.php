<!DOCTYPE html>
<html>
<head>
    @yield('title')
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" type="text/css" href="/assets/lib/toastr/toastr.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/app.min.css">

</head>
<body @if(!Auth::check()) class="cover" @endif>
@include('navbar')
<main class="container-fluid">
    <div id="flash-message" class="alert" role="alert">...</div>
    @yield('content')
</main>
</div>
<script type="text/javascript" src="/assets/lib/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/lib/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="/assets/lib/masonry/dist/masonry.pkgd.min.js"></script>
<script type="text/javascript" src="/assets/lib/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/lib/jscroll/jquery.jscroll.min.js"></script>
<script type="text/javascript" src="/assets/lib/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/assets/lib/jt.timepicker/jquery.timepicker.min.js"></script>
@if(env('APP_ENV') === 'local')
    <script type="text/javascript" src="/assets/js/app.js"></script>
@else
    <script type="text/javascript" src="/assets/js/app.min.js"></script>
@endif
</body>
</html>