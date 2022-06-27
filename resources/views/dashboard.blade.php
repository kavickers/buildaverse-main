<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="navigation"></x-slot>
    
    <div class="text-2xl fw-semibold mb-1">Recently Played Games</div>
    <div class="row gy-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="card overflow-hidden">
                <span class="position-absolute end-0 top-0 m-2 text-xs py-1 user-select-none pointer-events-none fw-semibold bg-primary text-white shadow-sm px-2 rounded">10 Playing</span>
                <img src="img/games/game1.png" class="img-fluid" width="300px" />
                <div class="p-2">
                    <div class="d-flex gap-2 align-items-center">
                        <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="50" />
                        <div>
                            <a href="#" class="text-light fw-semibold text-lg lh-1">Hell Obby</a>
                            <div class="text-muted truncate text-sm lh-1">
                                By: <a href="#" class="fw-semibold">c0ncrete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row gy-3">
        <div class="col-md-4">
            <div class="text-2xl fw-semibold mb-1">About Me</div>
            <div class="card card-body text-center mb-3">
                <img src="{{ auth()->user()->get_avatar() }}" class="img-fluid" />
                <div class="text-2xl fw-semibold mt-2">{{ auth()->user()->username }}</div>
                <div class="divider my-2">STATISTICS</div>
                <div class="row">
                    <div class="col-4">
                        <div class="text-bold">{{ auth()->user()->get_short_num(auth()->user()->getFriends()->count()) }}</div>
                        <div class="text-muted text-sm truncate">FRIENDS</div>
                    </div>
                    <div class="col-4">
                        <div class="text-bold">0</div>
                        <div class="text-muted text-sm truncate">VISITS</div>
                    </div>
                    <div class="col-4">
                        <div class="text-bold">{{ auth()->user()->get_short_num(auth()->user()->posts()) }}</div>
                        <div class="text-muted text-sm truncate">POSTS</div>
                    </div>
                </div>
            </div>
            <div class="text-2xl fw-semibold mb-1">Blog Updates</div>
            <div class="card p-3">
                @foreach($posts as $post)
                    <div class="section">
                        <div class="d-flex gap-3 align-items-center">
                            <div>
                                <i class="bi bi-rss text-4xl text-warning"></i>
                            </div>
                            <a href="{{ $post['url'] }}" class="d-block min-w-0">
                                <div class="truncate text-lg text-light fw-semibold">
                                    {{ $post['title'] }}
                                </div>
                                <div class="truncate text-sm fw-normal text-muted">
                                    {{ $post['excerpt'] }}
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">
            <div class="text-2xl mb-1 fw-semibold">My Feed</div>
            <div class="card card-body">
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('user.blurb.update') }}" class="d-flex gap-2 w-100">
                        @csrf
                        <input type="text" name="text" class="form-control" placeholder="What's poppin', {{ auth()->user()->username }}?" value="{{ old('text') }}" required/>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
                <span id="feed-data">
                    @if($blurbs->isEmpty())
                        <p class="text-center mt-4">Your feed is empty :(</p>
                    @else
                        <hr class="mb-0" />
                        @include('components.load_user_feed')                        
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="mb-2">&nbsp;</div>
    <x-slot name="script">
        <script>
            var query = window.location.search;
            var pageUrl = '';
            if (query) {
                pageUrl = query + '&page=';
            } else {
                pageUrl = '?page=';
            }

            function loadMoreData(page) {
                $.ajax({
                        url: pageUrl + '' + page,
                        type: 'get',
                    })
                    .done(function(data) {
                        if (data.html == " ") {
                            return;
                        }
                        $("#feed-data").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log("Server not responding...");
                    });
            }

            var page = 1;
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    loadMoreData(page);
                }
            });
        </script>
    </x-slot>
</x-app-layout>