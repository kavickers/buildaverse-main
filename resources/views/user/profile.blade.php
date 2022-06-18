<x-app-layout>
    <x-slot name="title">{{ $user->username }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row">
        <div class="col-12 d-block d-lg-none">
            <h1>{{ $user->username }} <i class="@if($user->isOnline()) text-success @else text-muted @endif bi bi-circle-fill text-sm align-middle mx-1" title="@if($user->isOnline()) Last seen now @else Last seen {{ $user->last_online->diffForHumans() }} @endif"></i></h1>
            <div class="card mb-4 text-center">
                <img class="card-img-top w-50 mx-auto my-4 my-lg-0" src="/img/avatar/human.png">
            </div>
        </div>

        <div class="col-lg-4 order-3 order-lg-1">
            <div class="d-none d-lg-block">
                <h4>Avatar</h4>
                <div class="card mb-4">
                    <img class="card-img-top p-4" src="/img/avatar/human.png">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Hat Name">
                                    <div class="slot has-item">
                                        <img src="/img/market/place-1.png" class="img-fluid">
                                    </div>
                                </a>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <div class="slot">
                                    <i class="bv-icon hard-hat"></i>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <div class="slot">
                                    <i class="bv-icon laugh"></i>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <div class="slot">
                                    <i class="bv-icon shirt"></i>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <div class="slot">
                                    <i class="bv-icon pants"></i>
                                </div>
                            </div>

                            <div class="col-6 col-sm-4 col-lg-6 col-xxl-4 d-flex justify-content-center my-3">
                                <div class="slot">
                                    <i class="bv-icon tools"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h4 class="mb-0">Friends</h4>
                <span class="text-bold"><a href="#" class="text-decoration-none">View all</a></span>
            </div>
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->getFriends()->count() > 0)
                        <div class="row">
                            @foreach($user->getFriends()->take(8) as $friend)
                                <div class="col-4 col-sm-3 my-2 position-relative" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $friend->username }}">
                                    <a href="{{ route('user.profile', $friend->id) }}"><img class="main-pfp w-100" src="/img/avatar/headshot.png"></a>
                                    <i class="status @if($friend->isOnline()) online @else offline @endif sm" title="@if($friend->isOnline()) Last seen now @else Last seen {{ $friend->last_online->diffForHumans() }} @endif"></i>
                                </div>
                            @endforeach
                        </div>
                    @else
                    <div class="text-center">LOL! No friends to be seen here!!<br>😂 LOSER - Jordan<br><a href="#">Get some friends -></a></div>
                    @endif
                </div>
            </div>

            <h4>Communities</h4>
            <div class="card mb-4">
                <div class="card-body">

                    <div class="section d-flex align-items-center">
                        <a href="#"><img class="bg-gray-500 rounded d-inline-block mr-2" src="/img/community/place-1.png" height="60" width="60"></a>
                        <div class="ml-2 w-100 min-w-0">
                            <h5 class="mb-0 truncate"><a href="#" class="truncate text-white fw-semibold">We Are Devs</a></h5>
                            <p class="truncate mb-0">15.5K members • <span class="text-danger">Private</span></p>
                        </div>
                    </div>

                    <div class="section d-flex align-items-center">
                        <a href="#"><img class="bg-gray-500 rounded d-inline-block mr-2" src="/img/community/place-2.png" height="60" width="60"></a>
                        <div class="ml-2 w-100 min-w-0">
                            <h5 class="mb-0 truncate"><a href="#" class="text-white fw-semibold">Epic Clan</a></h5>
                            <p class="truncate mb-0">15.5K members • <span class="text-success">Public</span></p>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-lg-8 order-2">

            <h4>User Info</h4>
            <div class="card mb-4">
                <div class="card-body">

                    <div class="d-md-flex justify-content-between mb-2">
                        <div class="me-2 min-w-0">
                            <h3 class="mb-0 d-none d-lg-block">
                                <div class="d-flex">
                                    <div class="profile-username-break">{{ $user->username }}<i class="@if($user->isOnline()) text-success @else text-muted @endif bi bi-circle-fill text-sm align-middle mx-2" style="vertical-align: 0.25rem!important;" title="@if($user->isOnline()) Last seen now @else Last seen {{ $user->last_online->diffForHumans() }} @endif"></i></div>

                                </div>
                            </h3>
                        </div>
                        <div class="mb-2 mb-sm-0 text-md-end w-100">
                            @auth
                                @if(auth()->user()->id != $user->id)
                                    @if(!auth()->user()->isFriendWith($user) && !auth()->user()->hasSentFriendRequestTo($user) && !$user->hasSentFriendRequestTo(auth()->user()))
                                        <button type="button" class="btn btn-primary mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add Friend" onclick="event.preventDefault();document.getElementById('profile-fr-send').submit();"><i class="bi-person-plus"></i></button>
                                        <form id="profile-fr-send" action="{{ route('user.friends.add', $user) }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                        @if(auth()->user()->hasFriendRequestFrom($user))
                                            <button type="button" class="btn btn-success mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Accept Request" onclick="event.preventDefault();document.getElementById('profile-fr-accept').submit();"><i class="bi-person-check"></i></button>
                                            <form id="profile-fr-accept" action="{{ route('user.friends.accept', $user) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        @endif
                                        @if($user->hasFriendRequestFrom(auth()->user()))
                                            <button type="button" class="btn btn-warning mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cancel Request" onclick="event.preventDefault();document.getElementById('profile-fr-cancel').submit();"><i class="bi-person-x"></i></button>
                                            <form id="profile-fr-cancel" action="{{ route('user.friends.remove', $user) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        @endif
                                        @if(auth()->user()->isFriendWith($user))
                                            <button type="button" class="btn btn-danger mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Remove Friend" onclick="event.preventDefault();document.getElementById('profile-fr-remove').submit();"><i class="bi-person-dash"></i></button>
                                            <form id="profile-fr-remove" action="{{ route('user.friends.remove', $user) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        @endif
                                @endif
                            @endauth
                            <button type="button" class="btn btn-secondary mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Message"><i class="bi-envelope-plus"></i></button>
                            <button type="button" class="btn btn-secondary mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Trade"><i class="bi-arrow-left-right"></i></button>
                            <a href="{{ route('report.user', $user) }}" type="button" class="btn btn-secondary mb-1 mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Report"><i class="bi-flag"></i></a>
                        </div>
                    </div>
                    <a href="#" class="bg-gray-500 text-white rounded d-inline-block p-1 mb-3">
                        <img class="rounded" src="/img/community/place-1.png" width="36" height="36">
                        <span class="h6 mx-1" style="line-height: 36px;">Guildaverse &nbsp;<i class="text-info bi-check-circle-fill"></i>&nbsp;</span>
                    </a>
                    <h5 class="mb-1">About</h5>
                    <p class="text-muted mb-3">
                        @if($user->biography)
                            {!! nl2br(e($user->biography)) !!}
                        @else
                            User has not set a biography.
                        @endif
                    </p>

                    @if (!empty($user->badges()))
                    <h5 class="mb-2">Achievements</h5>
                    <div class="row mb-3">

                        @foreach ($user->badges() as $badge)
                            <div class="col-lg-2 col-md-3 col-4 my-2 my-md-0">
                                <div data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $badge->name }}">
                                    <a href="#"><img class="w-100" src="{{ $badge->image }}"></a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    @endif
                    <h5 class="mb-2">Statistics</h5>

                    <div class="row row-tight text-center mt-3">
                        <div class="col-6 col-md-3 my-2 my-md-0">
                            <div class="text-bold">{{ $user->created_at->format('m/d/Y') }}</div>
                            <div class="text-muted text-small">JOINED</div>
                        </div>
                        <div class="col-6 col-md-3 my-2 my-md-0">
                            <div class="text-bold">{{ $user->get_short_num($user->getFriendsCount()) }}</div>
                            <div class="text-muted text-small">FRIENDS</div>
                        </div>
                        <div class="col-6 col-md-3 my-2 my-md-0">
                            <div class="text-bold">{{ $user->get_short_num($user->posts()) }}</div>
                            <div class="text-muted text-small">POSTS</div>
                        </div>
                        <div class="col-6 col-md-3 my-2 my-md-0">
                            <div class="text-bold">{{ $user->get_short_num($user->views) }}</div>
                            <div class="text-muted text-small">VIEWS</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-none d-lg-block">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 class="mb-0">Worlds</h4>
                    <span class="text-bold"><a href="#" class="text-decoration-none">View all</a></span>
                </div>
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div id="carouselGameCaptions" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            </div>
                            <div class="carousel-inner rounded">
                                <div class="carousel-item active">
                                    <img src="/img/games/game1.png" class="d-block w-100 bg-gray-500" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Totally a Game</h5>
                                        <p>This is totally a description for a totally real game :D</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="/img/games/game2.png" class="d-block w-100 bg-gray-500" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Totally a Game 2</h5>
                                        <p>A sequel to the totally-est of all games: Totally a Game

                                        </p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="/img/games/game1.png" class="d-block w-100 bg-gray-500" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Third game</h5>
                                        <p>Some representative placeholder content for the third slide.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="/img/games/game2.png" class="d-block w-100 bg-gray-500" alt="...">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Fourth game</h5>
                                        <p>Some representative placeholder content for the fourth slide.</p>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselGameCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselGameCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
