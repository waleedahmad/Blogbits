<!DOCTYPE html>
<html>
<head>
    @yield('title')
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{mix('/assets/bundle/app.css')}}">
    <link rel="icon" href="/favicon.png" type="image/png" sizes="16x16">
</head>
<body>
@include('navbar')
<main class="container-fluid">
    <div id="flash-message" class="alert" role="alert">...</div>
    @yield('content')
</main>
</div>
@if(env('APP_ENV') === 'local')
    <script type="text/javascript" src="{{mix('/assets/bundle/app.js')}}"></script>
@else
    <script type="text/javascript" src="/assets/js/app.min.js"></script>
@endif
</body>
</html>