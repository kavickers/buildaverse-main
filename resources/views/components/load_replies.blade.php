@foreach($replies as $reply)
    <!-- Begin reply -->
    <div class="card mb-3">
        <?php
          if($reply->quote_id != NULL)
          {
              $quote = NULL;
              if($reply->quote_type == 1)
              {
                  $quote = \App\Models\Thread::where('id', $reply->quote_id)->get()->first();
              } elseif($reply->quote_type == 2) {
                  $quote = \App\Models\Reply::where('id', $reply->quote_id)->get()->first();
              }

              if($quote->exists)
              {
        ?>

            <!-- Begin quote -->
            <div class="card-header p-2 px-4 text-muted truncate">
                <i class="bi bi-reply-fill text-2xl me-1"></i>
                <span class="fw-semibold">{{ $quote->owner->username }}</span> &mdash; {{ $quote->body }}
            </div>
            <!-- End quote -->
        <?php
              }
          }
        ?>
        <div class="p-3">
            <div class="d-flex justify-content-between">
                <div class="min-w-0">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('user.profile', $reply->owner->id) }}">
                            <img src="/img/avatar/headshot.png" class="headshot rounded-circle d-none d-md-block" width="80" />
                            <img src="/img/avatar/headshot.png" class="headshot rounded-circle d-block d-md-none" width="70" />
                        </a>
                        <div class="ms-3 min-w-0">
                            <a href="{{ route('user.profile', $reply->owner->id) }}" class="d-block truncate @if($reply->owner->power > 0) text-danger @else text-light @endif text-xl fw-semibold">
                                {{ $reply->owner->username }}
                            </a>
                            <div class="text-sm text-muted truncate">{{ $reply->owner->get_short_num($reply->owner->posts()) }} posts</div>
                            <div class="text-sm text-muted mt-1">{{ $reply->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div>
                        {!! nl2br(e($reply->body)) !!}
                    </div>
                    <div class="mt-2 fst-italic text-muted">{{ nl2br($reply->owner->signature) }}</div>
                    <div class="mt-2 truncate">
                        @auth
                        <button class="text-danger fw-normal border-0 bg-transparent p-0 like-reply-btn" data-id="{{ $reply->id }}">
                            <i id="like-reply-{{ $reply->id }}" class="bi @if(auth()->user()->forumHasLiked($reply->id)) bi-heart-fill @else bi-heart @endif me-1"></i>
                            <span class="text-sm" id="r-{{ $reply->id }}-count">{{ $reply->owner->get_short_num($reply->likes()->count()) }}</span>
                        </button>
                        @else
                            <span class="text-danger fw-normal">
                                <i class="bi bi-heart me-1"></i><span class="text-sm">{{ $reply->owner->get_short_num($reply->likes()->count()) }}</span>
                            </span>
                        @endauth
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

                            @endif
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('report.reply', $reply->id) }}">Report</a>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('forum.thread.quote', ['thread' => $reply->thread_id, 'quote_id' => $reply->id, 'quote_type' => 2]) }}" class="text-light text-2xl"><i class="bi bi-quote"></i></a>
                </div>
                @endauth
            </div>
        </div>
    </div>
    <!-- End reply -->
@endforeach
