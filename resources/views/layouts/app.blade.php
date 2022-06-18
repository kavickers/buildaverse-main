<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }} / Buildaverse</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('img/branding/favicon.png') }}" />

        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.1.1/css/pro.min.css">
    </head>
    <body {{ $attributes }}>
        @if(isset($navigation) && $navigation)
            @include('layouts.navigation')
        @endif

        <!-- BEGIN CONTENT -->
        <main class="container @if(!request()->is('login', 'register')) main-section pb-5 pb-md-0 @endif">
            @if(!request()->is('login', 'register'))
                <div class="d-flex justify-content-center mb-md-4 mt-5 mt-md-0">
                    <div>
                        <img class="rounded img-fluid" src="{{ asset('img/ads/leaderboard.png') }}"><br>
                        <span class="text-muted small my-0" >Advertisement</span>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>
        <!-- END CONTENT -->

        <!-- JavaScript -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        @include('components.toastr')
        @auth
        <script>
            Echo.private('App.Models.User.'+{{ auth()->user()->id }})
                .listen('UserNotification', (e) => {
                    if(e.type) {
                        if(e.url) {
                            toastr.info(""+e.message, "Notification", {iconClass: ""+e.type, onclick: function () { window.location.href = ''+e.url }})
                        } else {
                            toastr.info(""+e.message, "Notification", {iconClass: ""+e.type})
                        }
                    } else {
                        toastr.info(""+e.message, "Notification");
                    }
                });
        </script>
        @endauth

        @if(isset($script) && $script)
            {{ $script }}
        @endif
    </body>
</html>
