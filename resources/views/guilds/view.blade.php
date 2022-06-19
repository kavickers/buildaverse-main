@extends('layouts.app')

@section('title', 'Guild Name')

@section('content')
    <div class="row row-eq-spacing justify-content-center">
        <div class="col-12">
            <div class="card p-5 pl-15 pr-15 m-0 h-50">
                <span class="font-size-24 mb-5 text-left">username's Guild</span>
                <div class="float-right align-middle hidden-md-and-down">
                    <p class="p-0 m-0 font-size-24">Vault:
                        <span class="ml-10"></span>
                        <i class="font-size-18 cash fas fa-money-bill"></i>
                        <span class="font-size-18 font-weight-semi-bold mr-10">100</span>
                        <i class="font-size-18 coin fas fa-coins"></i>
                        <span class="font-size-18 font-weight-semi-bold">1K+</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="v-spacer"></div>
        <div class="col-md-3 text-center">
            <div class="card p-0 m-0 h-250">
                <img src="/static/img/bv_dark.png" class="img-fluid">
            </div>

            <h5 class="mb-5">Owner: <a href="#">username</a></h5>

            <a href="#" class="no-style">
                <div class="card guild-button guild-active">
                    <p class="font-weight-semi-bold p-10 m-10 text-white">Home</p>
                </div>
            </a>
            <a href="#" class="no-style">
                <div class="card guild-button">
                    <p class="font-weight-semi-bold p-10 m-10 text-white">Members</p>
                </div>
            </a>
            <a href="#" class="no-style">
                <div class="card guild-button">
                    <p class="font-weight-semi-bold p-10 m-10 text-white">Relations</p>
                </div>
            </a>
            <a href="#" class="no-style">
                <div class="card guild-button">
                    <p class="font-weight-semi-bold p-10 m-10 text-white">Market</p>
                </div>
            </a>
            <br>
            <div class="card p-0 m-0">
                <div class="row">
                    <div class="col-12 text-left">
                        <h5 class="p-0 m-0 ml-10 mt-10">Statistics</h5>
                    </div>
                </div>
                <div class="m-0 mt-10 p-0 border-bottom"></div>
                <div id="statisticsSlides" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <h4 class="mb-0 mt-10">635</h4>
                            <p class="mt-0">members</p>
                        </div>
                        <div class="carousel-item">
                            <h4 class="mb-0 mt-10">25</h4>
                            <p class="mt-0">items</p>
                        </div>
                        <div class="carousel-item">
                            <h4 class="mb-0 mt-10">3</h4>
                            <p class="mt-0">worlds</p>
                        </div>
                        <div class="carousel-item">
                            <h4 class="mb-0 mt-10">165K+</h4>
                            <p class="mt-0">visits</p>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#statisticsSlides" role="button" data-slide="prev">
                        <i class="font-size-18 text-white fas fa-arrow-left"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#statisticsSlides" role="button" data-slide="next">
                        <i class="font-size-18 text-white fas fa-arrow-right"></i>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card p-5 h-250 overflow-scroll">
                <p class="p-card pt-5 pb-0">This is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omgThis is the description of the guild omg</p>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 pr-md-15">
                    <div class="card m-0 p-0 p-20 h-300">
                        <div class="pb-5">
                            <h4 class="card-title mb-0">Latest Announcement</h4>
                        </div>
                        @auth
                        <form action="#" method="POST" class="pb-15">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="announcement" class="form-control" placeholder="Enter new announcement here...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">Submit</button>
                                </div>
                            </div>
                        </form>
                        @endauth
                        <div class="row">
                            <div class="col-4">
                                <img class="img-fluid w-250" src="/static/img/avatar.png">
                            </div>
                            <div class="col-8">
                                <div class="box p-left top w-auto p-0 pl-20 pr-20 mb-5 m-0 mt-20 ml-10">
                                    <p>announcement text!! just wait w aitwai twaitwa it waitwaitwai t wait wait wait waitwai t wai twait wait wa it wawaitwait wai wait twa wait waitwait wait wait wait it it :)))</p>
                                </div>
                                <small class="m-0 ml-10"><a href="#">Username</a>, 37 minutes ago</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="v-spacer hidden-md-and-up"></div>
                <div class="col-sm-12 col-md-6 pl-md-15">
                    <div class="card m-0 h-300">
                        <div id="worldsSlides" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#worldsSlides" data-slide-to="0" class="active"></li>
                                <li data-target="#worldsSlides" data-slide-to="1"></li>
                                <li data-target="#worldsSlides" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="/static/img/thumbnail.png" class="d-block img-fluid">
                                    <div class="carousel-caption d-none d-md-block">
                                        <a href="#" class="no-style">
                                            <h4 class="mb-0">World Name</h4>
                                        </a>
                                        <p class="mt-0">153 users online</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="/static/img/thumbnail.png" class="d-block img-fluid">
                                    <div class="carousel-caption d-none d-md-block">
                                        <a href="#" class="no-style">
                                            <h4 class="mb-0 text-truncate">World Name</h4>
                                        </a>
                                        <p class="mt-0">365 users online</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="/static/img/thumbnail.png" class="d-block img-fluid">
                                    <div class="carousel-caption d-none d-md-block">
                                        <a href="#" class="no-style">
                                            <h4 class="mb-0 text-truncate">World Name</h4>
                                        </a>
                                        <p class="mt-0">25 users online</p>
                                    </div>
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#worldsSlides" role="button" data-slide="prev">
                                <i class="font-size-24 text-white fas fa-chevron-left"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#worldsSlides" role="button" data-slide="next">
                                <i class="font-size-24 text-white fas fa-chevron-right"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card p-0 mt-40">
                <div class="card-header">
                    <div class="font-size-14 font-weight-medium">Wall</div>
                </div>
                <div class="p-0">
                    @auth
                        <div class="border-top p-10">
                            <form method="POST" action="#">
                                @csrf
                                <div class="mb-10">
                                    <textarea class="form-control" name="body" id="post" placeholder="Enter post here..."></textarea>
                                </div>
                                <div class="text-right">
                                    <input class="btn btn-primary btn-sm" type="submit" value="Post">
                                </div>
                            </form>
                        </div>
                    @endauth
                        <div class="text-center border-top p-10">
                            <p>No posts found</p>
                        </div>
                        <!-- Start comment -->
                            <div class="p-10 border-top">
                                <div class="comment-box">
                                    <a href="#">
                                        <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64">
                                    </a>
                                    <div class="comment-text pl-80 pb-8">
                                        <a href="#" class="float-right report"><i class="far fa-flag"></i></a>
                                        <span class="font-size-13"><a href="#" class="font-weight-semi-bold">Username</a></span><span class="text-muted font-size-10 ml-5"><i class="fas fa-clock"></i> 1 minute ago</span>
                                        <div class="font-size-14 mb-15">This is a default wall post! Hello there.</div>
                                        <div class="text-right admin-link font-size-12">
                                            <a href="https://antelope.is/guilds/#/wall/#delete" class="font-weight-normal">DELETE</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End comment -->
                        <!-- pagination -->
                        <br>

                </div>
            </div>
        </div>
    </div>
@endsection
