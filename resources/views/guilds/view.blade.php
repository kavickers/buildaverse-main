<x-app-layout>
    <x-slot name="title">{{ $guild->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card p-3 px-4 mb-3 text-center text-md-start">
        <div class="d-md-flex justify-content-between align-items-center">
            <div class="mb-3 mb-md-0">
                <div class="text-3xl fw-semibold">{{ $guild->name }}</div>
                <div class="text-muted">
                    Creator: <a href="{{ route('user.profile', $guild->owner->id) }}" class="fw-semibold">{{ $guild->owner->username }}</a>
                </div>
            </div>
            @if($guild->is_vault_viewable)
                <div class="text-center">
                    <div class="text-bold">
                        <span class="text-success text-xl me-2">
                            <i class="bi bi-cash-stack text-2xl me-1 align-middle"></i> {{ $guild->get_short_num($guild->cash) }}
                        </span>
                        <span class="text-warning text-xl">
                            <i class="bi bi-coin text-2xl me-1 align-middle"></i> {{ $guild->get_short_num($guild->coins) }}
                        </span>
                    </div>
                    <div class="text-muted">VAULT</div>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <img src="{{ $guild->thumbnail() }}" class="img-fluid p-5 pb-3" />
                <div class="row text-center justify-content-center p-3">
                    <div class="col-6">
                        <div class="text-bold text-xl">{{ $guild->get_short_num($guild->members()->count()) }}</div>
                        <div class="text-muted">MEMBERS</div>
                    </div>
                    @auth
                        @if(auth()->user()->isInGuild($guild->id))
                            <div class="col-6">
                                <div class="text-bold text-xl truncate">{{ auth()->user()->rankInGuild($guild->id)->name }}</div>
                                <div class="text-muted">MY RANK</div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="nav flex-column nav-pills mb-3 mb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="home-tab" data-bs-toggle="pill" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                    Home
                </button>
                <button class="nav-link" id="members-tab" data-bs-toggle="pill" data-bs-target="#members" type="button" role="tab" aria-controls="members" aria-selected="false">
                    Members
                </button>
                <button class="nav-link" id="relations-tab" data-bs-toggle="pill" data-bs-target="#relations" type="button" role="tab" aria-controls="Relations" aria-selected="false">
                    Relations
                </button>
                <button class="nav-link" id="market-tab" data-bs-toggle="pill" data-bs-target="#market" type="button" role="tab" aria-controls="Market" aria-selected="false">
                    Market
                </button>
            </div>
        </div>
        <div class="col-md-8">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="card card-body mb-3" style="height: 315px; overflow-y: scroll">
                    @if($guild->desc != null)
                        {!! nl2br(e($guild->desc)) !!}
                    @else
                        No description set.
                    @endif
                </div>
                    <h4>Latest Announcement</h4>
                    <div class="card card-body mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="#" class="text-center">
                                    <img src="img/avatar/blocky.png" class="img-fluid mb-4 mb-md-0" />
                                </a>
                            </div>
                            <div class="col-md-9">
                                <div class="card card-announcement card-body mb-1">
                                    hello there we are going to do a summon sid ritual please
                                    make sure you're there by 3pm next wednesday!
                                </div>
                                <div class="text-sm text-muted">
                                    Posted by <a href="#" class="fw-semibold">Sid</a>, 8 hours
                                    ago
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4>Worlds</h4>
                    <div class="card card-body mb-3">
                        <div id="carouselGameCaptions" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselGameCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            </div>
                            <div class="carousel-inner rounded">
                                <div class="carousel-item active">
                                    <img src="img/games/game1.png" class="d-block w-100 bg-gray-500" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Totally a Game</h5>
                                        <p>
                                            This is totally a description for a totally real game
                                            :D
                                        </p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="img/games/game2.png" class="d-block w-100 bg-gray-500" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Totally a Game 2</h5>
                                        <p>
                                            A sequel to the totally-est of all games: Totally a
                                            Game
                                        </p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="img/games/game1.png" class="d-block w-100 bg-gray-500" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Third game</h5>
                                        <p>
                                            Some representative placeholder content for the third
                                            slide.
                                        </p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="img/games/game2.png" class="d-block w-100 bg-gray-500" alt="..." />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Fourth game</h5>
                                        <p>
                                            Some representative placeholder content for the fourth
                                            slide.
                                        </p>
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
                    @auth
                        @if(auth()->user()->isInGuild($guild->id))
                            <h4>Community Wall</h4>
                            <div class="card card-body">
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control" placeholder="What do you want to post to this community's wall?" />
                                    <button type="submit" class="btn btn-success px-3">
                                        Post
                                    </button>
                                </div>
                                <hr class="mb-0" />
                                <div class="section">
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                                        <div class="w-100">
                                            <a href="#" class="text-xl fw-semibold text-light">Kyle</a>
                                            <div class="text-muted mb-2">
                                                this item looks so nice but i was too lazy to
                                                participate in the egg hunt so i didn't get it... sucks
                                                to suck!
                                            </div>
                                            <div class="text-muted text-sm">Posted 8 minutes ago</div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#">Report</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="section">
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                                        <div class="w-100">
                                            <a href="#" class="text-xl fw-semibold text-light">Kyle</a>
                                            <div class="text-muted mb-2">
                                                this item looks so nice but i was too lazy to
                                                participate in the egg hunt so i didn't get it... sucks
                                                to suck!
                                            </div>
                                            <div class="text-muted text-sm">Posted 8 minutes ago</div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#">Report</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="section">
                                    <div class="d-flex gap-3 align-items-center">
                                        <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                                        <div class="w-100">
                                            <a href="#" class="text-xl fw-semibold text-light">Kyle</a>
                                            <div class="text-muted mb-2">
                                                this item looks so nice but i was too lazy to
                                                participate in the egg hunt so i didn't get it... sucks
                                                to suck!
                                            </div>
                                            <div class="text-muted text-sm">Posted 8 minutes ago</div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical text-xl"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li>
                                                    <span class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mb-1" />
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#">Report</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="tab-pane" id="members" role="tabpanel" aria-labelledby="members-tab" tabindex="0">
                    <h4>Members</h4>
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-control">
                                    @foreach ($guild->ranks() as $rank)
                                        <option value="{{ $rank->rank }}">{{ $rank->name }} ({{ $rank->memberCount() }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                            <div class="col-3 text-center mb-3">
                                <a href="#">
                                    <img src="img/avatar/human.png" class="img-fluid mb-1" />
                                </a>
                                <a href="#" class="d-block truncate fw-semibold">c0ncrete</a>
                            </div>
                        </div>
                        <nav class="mt-2">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="tab-pane" id="relations" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <h4>Relations</h4>
                    <h5>Allies</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-body position-relative text-center mb-5">
                                <a href="#">
                                    <div class="community-icon rounded-circle overflow-hidden">
                                        <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                    </div>
                                    <div class="text-xl fw-semibold text-light mt-4">
                                        Sid's Evil Lair
                                        <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                    </div>
                                    <div class="text-muted fw-normal line-clamp">
                                        join if u like sid and umm wanna write fanfictions about
                                        him
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body position-relative text-center mb-5">
                                <a href="#">
                                    <div class="community-icon rounded-circle overflow-hidden">
                                        <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                    </div>
                                    <div class="text-xl fw-semibold text-light mt-4">
                                        Sid's Evil Lair
                                        <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                    </div>
                                    <div class="text-muted fw-normal line-clamp">
                                        join if u like sid and umm wanna write fanfictions about
                                        him
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body position-relative text-center mb-5">
                                <a href="#">
                                    <div class="community-icon rounded-circle overflow-hidden">
                                        <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                    </div>
                                    <div class="text-xl fw-semibold text-light mt-4">
                                        Sid's Evil Lair
                                        <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                    </div>
                                    <div class="text-muted fw-normal line-clamp">
                                        join if u like sid and umm wanna write fanfictions about
                                        him
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body position-relative text-center mb-5">
                                <a href="#">
                                    <div class="community-icon rounded-circle overflow-hidden">
                                        <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                    </div>
                                    <div class="text-xl fw-semibold text-light mt-4">
                                        Sid's Evil Lair
                                        <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                    </div>
                                    <div class="text-muted fw-normal line-clamp">
                                        join if u like sid and umm wanna write fanfictions about
                                        him
                                    </div>
                                </a>
                            </div>
                        </div>
                        <h5>Enemies</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-body position-relative text-center mb-5">
                                    <a href="#">
                                        <div class="community-icon rounded-circle overflow-hidden">
                                            <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                        </div>
                                        <div class="text-xl fw-semibold text-light mt-4">
                                            Sid's Evil Lair
                                            <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                        </div>
                                        <div class="text-muted fw-normal line-clamp">
                                            join if u like sid and umm wanna write fanfictions
                                            about him
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-body position-relative text-center mb-5">
                                    <a href="#">
                                        <div class="community-icon rounded-circle overflow-hidden">
                                            <img src="img/branding/dark_icon.svg" class="img-fluid" />
                                        </div>
                                        <div class="text-xl fw-semibold text-light mt-4">
                                            Sid's Evil Lair
                                            <span class="text-sm fw-normal text-muted">&bullet; 18k+ members</span>
                                        </div>
                                        <div class="text-muted fw-normal line-clamp">
                                            join if u like sid and umm wanna write fanfictions
                                            about him
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="market" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <h4>Community Market</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/human.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/human.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/human.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/human.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card overflow-hidden position-relative h-100">
                                <div class="d-flex d-md-block align-items-center">
                                    <a href="#">
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-none d-md-block p-3" />
                                        <img src="img/avatar/blocky.png" class="img-fluid item-preview d-block d-md-none p-3" width="160" />
                                    </a>
                                    <div class="px-3 py-2 min-w-0">
                                        <a href="#" class="text-xl fw-semibold text-light d-block truncate">sid fanfic shirt owo</a>
                                        <div class="text-muted truncate text-sm mb-2">
                                            Creator:
                                            <a href="#" class="fw-semibold">Sid's Evil Lair</a>
                                        </div>
                                        <div class="text-md-center text-sm">
                                            <span class="d-md-block text-success fw-semibold me-2 me-md-0">
                                                <i class="bi bi-cash-stack text-md me-1 align-middle"></i>10,000
                                            </span>
                                            <span class="d-md-block text-warning fw-semibold me-2 me-md-0">
                                                <i class="bi bi-coin text-md me-1 align-middle"></i>120,000
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>