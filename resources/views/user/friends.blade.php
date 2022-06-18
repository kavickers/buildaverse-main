@extends('layouts.app')

@section('title', 'Friends')

@section('content')
    <div class="row row-eq-spacing justify-content-center">
        <div class="col-md-10">
            <h4 class="text-white font-weight-semi-bold pl-5 pr-5">My Friends</h4>
        </div>
        <div class="col-md-10">

            <ul class="tabs border ml-5 mr-5">
                <li class="tab col-6 active" data-tab-target="#requests">REQUESTS</li>
                <li class="tab col-6" data-tab-target="#friends">FRIENDS</li>
            </ul>

            <div class="tab-content">
                <div class="active" id="requests" data-tab-content>

                    <div class="row align-items-center pl-5 mt-5">
                        <div class="col pt-0 m-0 text-left">
                            <h5 class="font-weight-semi-bold text-white m-0">Requests ({{ auth()->user()->getFriendRequests()->count() }})</h5>
                        </div>
                        @if(auth()->user()->getFriendRequests()->count() >= 1)
                            <div class="col p-0 m-0 text-right">
                                <form method="POST" action="{{ route('user.friends.accept.all') }}">
                                    @csrf
                                    <input class="btn btn-link" type="submit" value="Accept All">
                                </form>
                                <form method="POST" action="{{ route('user.friends.decline.all') }}">
                                    @csrf
                                    <input class="btn btn-link" type="submit" value="Decline All">
                                </form>
                            </div>
                        @endif
                    </div>

                    <div class="row row-eq-spacing p-0 mt-10">

                    @foreach($requests as $request)
                        <!-- BEGIN USER -->
                            <div class="col-6 col-sm-3 p-0 pl-5 pr-5 pb-10">
                                <div class="card p-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="p-10 m-0">
                                                <a href="{{ route('user.profile', $request->sender->id) }}">
                                                    <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64 @if($request->sender->isOnline()) online-website @endif">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col text-truncate">
                                            <p class="text-truncate m-0">
                                                <a href="{{ route('user.profile', $request->sender->id) }}" class="no-style font-weight-semi-bold">{{ $request->sender->username }}</a>
                                            </p>
                                            @if($request->sender->isOnline())
                                                <p class="text-truncate font-size-12 m-0 text-success">Website - Browsing</p>
                                            @else
                                                <p class="text-truncate font-size-12 m-0 text-muted">Offline</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row align-items-center p-10">
                                        <div class="col">
                                            <form method="POST" action="{{ route('user.friends.accept', $request->sender->id) }}">
                                                @csrf
                                                <input class="btn btn-success w-full" type="submit" value="Accept">
                                            </form>
                                        </div>
                                        <span class="m-5 hidden-md-and-down"></span>
                                        <div class="btn-spacer hidden-md-and-up"></div>
                                        <div class="col">
                                            <form method="POST" action="{{ route('user.friends.decline', $request->sender->id) }}">
                                                @csrf
                                                <input class="btn btn-danger w-full" type="submit" value="Decline">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END USER -->
                    @endforeach

                    </div>

                </div>

                <div class="" id="friends" data-tab-content>

                    <h5 class="font-weight-semi-bold text-white pl-5 mb-10 mt-10">Friends ({{ count($friends) }})</h5>
                    <div class="row row-eq-spacing p-0 mt-10">

                    @foreach($friends as $friend)
                            <!-- BEGIN USER -->
                            <div class="col-6 col-sm-3 p-0 pl-5 pr-5 pb-10">
                                <div class="card p-0">
                                        <span class="float-right mt-10 mr-10">
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" class="edit-ellipsis"><i class="fas fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <form method="POST" id="delete-{{ $friend->id }}" action="{{ route('user.friends.remove', $friend->id) }}">
                                                        @csrf
                                                        <a href="#" onclick="event.preventDefault();document.getElementById('delete-{{ $friend->id }}').submit();" class="dropdown-item"><i class="fas fa-user-slash text-danger"></i> Unfriend</a>
                                                    </form>
                                                </div>
                                            </div>
                                        </span>
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="p-10 m-0">
                                                <a href="{{ route('user.profile', $friend->id) }}">
                                                    <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64 @if($friend->isOnline()) online-website @endif">
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col text-truncate">
                                            <p class="text-truncate m-0">
                                                <a href="{{ route('user.profile', $friend->id) }}" class="no-style font-weight-semi-bold">{{ $friend->username }}</a>
                                            </p>
                                            @if($friend->isOnline())
                                                <p class="text-truncate font-size-12 m-0 text-success">Website - Browsing</p>
                                            @else
                                                <p class="text-truncate font-size-12 m-0 text-muted">Offline</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END USER -->
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        @if(count($errors))
        @foreach($errors->all() as $error)
        toastDangerAlert("Error", "<?php echo $error; ?>");
        @endforeach
        @endif

        @if(session('new_friend'))
        toastSuccessAlert("Success", "<?php echo session('new_friend'); ?>");
        @endif
        @if(session('declined_friend'))
        toastSuccessAlert("Success", "<?php echo session('declined_friend'); ?>");
        @endif
        @if(session('deleted_friend'))
        toastSuccessAlert("Success", "<?php echo session('deleted_friend'); ?>");
        @endif
        @if(session('timeout'))
        toastDangerAlert("Error", "<?php echo session('timeout'); ?>");
        @endif

        function toastDangerAlert(title, content) {
            halfmoon.initStickyAlert({
                content: content,
                title: title,
                alertType: "alert-danger",
                fillType: "filled"
            });
        }

        function toastSuccessAlert(title, content) {
            halfmoon.initStickyAlert({
                content: content,
                title: title,
                alertType: "alert-success",
                fillType: "filled"
            });
        }
    </script>

    <script>
        /* Custom tab system */
        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabContents = document.querySelectorAll('[data-tab-content');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = document.querySelector(tab.dataset.tabTarget)
                tabContents.forEach(tabContent => {
                    tabContent.classList.remove('active');
                });
                tabs.forEach(tab => {
                    tab.classList.remove('active');
                });
                tab.classList.add('active');
                target.classList.add('active');
            });
        });
    </script>
@endsection
