<x-app-layout>
    <x-slot name="title">{{ $thread->title }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card p-3 px-4 mb-2 mt-md-2 mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('forum.index') }}" class="text-xl text-light">
                <i class="bi bi-chevron-left"></i>
            </a>
            @auth
                <div>
                    <a href="#" class="text-xl text-white" data-bs-toggle="tooltip" title="My Threads">
                        <i class="bi bi-chat-left-text"></i>
                    </a>
                </div>
            @endauth
        </div>
    </div>
    <div class="text-end">
        @auth
            @if(!$thread->locked)
                <a href="{{ route('forum.thread.reply', $thread->id) }}" class="btn btn-primary btn-rounded d-none d-md-inline-block" style="margin-top: 2px">Create Reply</a>
                <a href="{{ route('forum.thread.reply', $thread->id) }}" class="btn btn-primary btn-floating d-flex d-md-none">
                    <i class="bi bi-plus"></i>
                </a>
            @endif
        @endauth
    </div>
    <h2 class="mt-5 mt-md-0">
        @if($thread->locked)
            <span class="badge bg-secondary text-sm fw-semibold position-relative" style="bottom:4px;">Locked</span>
        @endif
        @if($thread->stuck || $thread->pinned)
            <span class="badge bg-danger text-sm fw-semibold position-relative" style="bottom:4px;">Pinned</span>
        @endif
        {!! nl2br(e($thread->title)) !!}
    </h2>
    <div class="card mb-3 mt-2 mt-md-0">
        <div class="p-3">
            <div class="d-flex justify-content-between">
                <div class="min-w-0">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('user.profile', $thread->owner->id) }}">
                            <img src="/img/avatar/headshot.png" class="headshot rounded-circle d-none d-md-block" width="80" />
                            <img src="/img/avatar/headshot.png" class="headshot rounded-circle d-block d-md-none" width="70" />
                        </a>
                        <div class="ms-3 min-w-0">
                            <a href="{{ route('user.profile', $thread->owner->id) }}" class="d-block truncate @if($thread->owner->power > 0) text-danger @else text-light @endif text-xl fw-semibold">
                                {{ $thread->owner->username }}
                            </a>
                            <div class="text-sm text-muted truncate">{{ $thread->owner->get_short_num($thread->owner->posts()) }} posts</div>
                            <div class="text-sm text-muted mt-1">{{ $thread->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div>
                        {!! nl2br(e($thread->body)) !!}
                    </div>
                    <div class="mt-2 fst-italic text-muted">{{ nl2br($thread->owner->signature) }}</div>
                    <div class="mt-2 truncate">
                            <span class="text-primary fw-normal">
                                <i class="bi bi-chat-left-text me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->replies()->count()) }}</span>
                            </span>
                        <span class="mx-2 text-muted text-xs">&bullet;</span>
                        @auth
                            <button class="text-danger fw-normal border-0 bg-transparent p-0 like-thread-btn" data-id="{{ $thread->id }}">
                                <i id="like-thread" class="bi @if(auth()->user()->forumHasLiked($thread->id)) bi-heart-fill @else bi-heart @endif me-1"></i>
                                <span class="text-sm" id="th-{{ $thread->id }}-count">{{ $thread->owner->get_short_num($thread->likes()->count()) }}</span>
                            </button>
                        @else
                            <span class="text-danger fw-normal">
                                <i class="bi bi-heart me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->likes()->count()) }}</span>
                            </span>
                        @endauth
                        <span class="mx-2 text-muted text-xs">&bullet;</span>
                        <span class="text-info fw-normal">
                            <i class="bi bi-eye me-1"></i><span class="text-sm">{{ $thread->owner->get_short_num($thread->views) }}</span>
                        </span>
                    </div>
                </div>
                @auth
                <div class="d-flex flex-column justify-content-between text-center shrink">
                    <div class="dropdown">
                        <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-xl"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li>
                                <span class="text-center dropdown-item-text notification-dropdown-title p-0">Actions</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider mb-1" />
                            </li>
                            @if(auth()->user()->power > 0)
                                @if(!$thread->stuck)
                                    @if($thread->pinned)
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('thread-unpin').submit()">Unpin</a>
                                        </li>
                                        <form method="POST" id="thread-unpin" action="{{ route('forum.thread.pin', $thread->id) }}" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('thread-pin').submit()">Pin</a>
                                        </li>
                                        <form method="POST" id="thread-pin" action="{{ route('forum.thread.pin', $thread->id) }}" class="d-none">
                                            @csrf
                                        </form>
                                    @endif
                                @endif
                                @if($thread->locked)
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('thread-unlock').submit()">Unlock</a>
                                    </li>
                                    <form method="POST" id="thread-unlock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();document.getElementById('thread-lock').submit()">Lock</a>
                                    </li>
                                    <form method="POST" id="thread-lock" action="{{ route('forum.thread.lock', $thread->id) }}" class="d-none">
                                        @csrf
                                    </form>
                                @endif
                            @endif
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('report.threads', $thread->id) }}">Report</a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('forum.thread.quote', ['thread' => $thread->id, 'quote_id' => $thread->id, 'quote_type' => 1]) }}" class="text-light text-2xl"><i class="bi bi-quote"></i></a>
                </div>
                @endauth
            </div>
        </div>
    </div>
    <span id="post-data">
        @include('components.load_replies')
    </span>
    <div class="mb-md-3">&nbsp;</div>

    <x-slot name="script">
        <script>
            var query = window.location.search;
            var pageUrl = '';
            if(query) {
                pageUrl = query+'&page=';
            } else {
                pageUrl = '?page=';
            }
            function loadMoreData(page) {
                $.ajax({
                    url:pageUrl+''+page,
                    type:'get',
                })
                    .done(function(data) {
                        if(data.html == " ") {
                            return;
                        }
                        $("#post-data").append(data.html);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log("Server not responding...");
                    });
            }

            var page = 1;
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    loadMoreData(page);
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.like-thread-btn', function(){
                var id = $(this).data('id');
                var c = $('#th-'+id+'-count').html();
                var cObj = $('#like-thread');

                $.ajax({
                    type:'POST',
                    url:'/forum/thread/'+id+'/like',
                    data:{id:id},
                    success:function(data){
                        if(jQuery.isEmptyObject(data.success)){
                            $('#th-'+id+'-count').html(parseInt(c)-1);
                            $(cObj).removeClass("bi-heart-fill");
                            $(cObj).addClass("bi-heart");
                        }else{
                            $('#th-'+id+'-count').html(parseInt(c)+1);
                            $(cObj).removeClass("bi-heart")
                            $(cObj).addClass("bi-heart-fill");
                        }
                    }
                });
            });

            $(document).on('click', '.like-reply-btn', function(){
                var id = $(this).data('id');
                var c = $('#r-'+id+'-count').html();
                var cObj = $('#like-reply-'+id);

                $.ajax({
                    type:'POST',
                    url:'/forum/reply/'+id+'/like',
                    data:{id:id},
                    success:function(data){
                        if(jQuery.isEmptyObject(data.success)){
                            $('#r-'+id+'-count').html(parseInt(c)-1);
                            $(cObj).removeClass("bi-heart-fill");
                            $(cObj).addClass("bi-heart");
                        }else{
                            $('#r-'+id+'-count').html(parseInt(c)+1);
                            $(cObj).removeClass("bi-heart")
                            $(cObj).addClass("bi-heart-fill");
                        }
                    }
                });
            });

        </script>
    </x-slot>
</x-app-layout>
