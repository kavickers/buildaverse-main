@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row row-eq-spacing">
        <!-- Left column -->
        <div class="col-md-4">

            <!-- User card start -->
            <div class="card p-20">
                <div class="text-center">
                    <img src="{{ Auth::user()->get_avatar() }}" class="w-200">
                    <div class="card-title">{{ Auth::user()->username }}</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm text-center">
                        <span class="font-size-12 text-white">{{ Auth::user()->getFriendsCount() }}</span>
                        <div class="text-muted font-size-12">FRIENDS</div>
                    </div>
                    <div class="col-sm text-center">
                        <span class="font-size-12 text-white">{{ Auth::user()->posts() }}</span>
                        <div class="text-muted font-size-12">POSTS</div>
                    </div>
                    <div class="col-sm text-center">
                        <span class="font-size-12 text-white">N/A</span>
                        <div class="text-muted font-size-12">VISITS</div>
                    </div>
                </div>
            </div>
            <!-- User card end -->

            <!-- Recently played start -->
            <div class="card p-14">

                <!-- Card title with view all start -->
                <div class="row">
                    <div class="col-8">
                        <div class="font-size-16 font-weight-medium">Recently Played</div>
                    </div>
                    <div class="col-4">
                        <div class="text-right mt-4 font-size-10 view-all text-muted">
                            <a href="#">VIEW ALL</a>
                        </div>
                    </div>
                </div>
                <!-- Card title with view all end -->

                <hr>

                <!-- Game carousel start -->
                <div class="game-carousel">
                    <div class="game-carousel-left">
                        <a href="#"><i class="fas fa-chevron-left" aria-hidden="true"></i></a>
                    </div>
                    <div class="game-carousel-image">
                        <img src="https://picsum.photos/270/135" class="img-fluid w-200">
                    </div>
                    <div class="game-carousel-right">
                        <a href="#"><i class="fas fa-chevron-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="game-carousel-title">HF3ka_</div>
                    <div class="game-carousel-sub">by Mark · 4 people playing</div>
                </div>
                <!-- Game carousel end -->

            </div>
            <!-- Recently played end-->

            <!-- Blog posts start -->
            <div class="card p-14">
                <div class="card-title font-size-16 font-weight-medium">News</div>
                <hr>

                <!-- Post start -->
                <div class="block">
                    <a href="#" class="font-weight-bold block">
                        <div class="text-truncate">
                            Security Incident: What it means for you and your data
                        </div>
                    </a>
                    <div>by <span class="font-weight-semi-bold">Kyle</span></div>
                    <span class="text-muted">October 10th, 2020 11:29 AM</span>
                </div>
                <hr>
                <!-- Post end -->

                <!-- Post start -->
                <div class="block">
                    <a href="#" class="font-weight-bold">
                        <div class="text-truncate">
                            A Test Blog
                        </div>
                    </a>
                    <div>by Kyle</div>
                    <span class="text-muted">October 4th, 2020 01:53 PM</span>
                </div>
                <hr>
                <!-- Post end -->

            </div>
            <!-- Blog posts end -->

        </div>
        <div class="v-spacer hidden-md-and-up"></div>
        <!-- Right column -->
        <div class="col-md-8">
            <!-- My feed start -->
            @livewire('show-feed')
            <!-- My feed end -->
        </div>
    </div>
@endsection
