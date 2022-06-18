@foreach($items as $item)
    <!-- Begin item -->
    <div class="col-md-3 col-xxl-2 mb-3">
        <div class="card overflow-hidden position-relative h-100">
            <div class="d-flex d-md-block align-items-center">
                @if($item->special) <div class="collectible-badge"></div> @endif
                @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1) <div class="timed-badge"></div> @endif
                <a href="{{ route('market.item', $item->id) }}">
                    <img src="{{ $item->get_render() }}" class="img-fluid item-preview d-none d-md-block @if($item->special) is-collectible @endif @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1) is-timed @endif" />
                    <img src="{{ $item->get_render() }}" class="item-preview d-block d-md-none @if($item->special) is-collectible @endif @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1) is-timed @endif" width="128" />
                </a>
                <div class="px-3 py-2 min-w-0">
                    <a href="{{ route('market.item', $item->id) }}" class="text-xl fw-semibold text-light d-block truncate">
                        {!! nl2br(e($item->name)) !!}
                    </a>
                    <div class="text-muted truncate text-sm mb-2">
                        Creator:
                        <a href="{{ route('user.profile', $item->creator_id) }}" class="fw-semibold @if($item->owner->power > 0) text-danger @endif">{{ $item->owner->username }}</a>
                    </div>
                    <div class="text-md-center text-sm">
                        @if(!$item->free())
                            @if($item->cash > 0)
                                <span class="d-sm-block d-md-inline-block text-success fw-semibold me-2">
									        <i class="bi bi-cash-stack text-md me-1 align-middle"></i>{{ $item->get_short_price($item->cash) }}
								        </span>
                            @endif
                            @if($item->coins > 0)
                                <span class="d-sm-block d-md-inline-block text-warning fw-semibold me-2">
									        <i class="bi bi-coin text-md me-1 align-middle"></i>{{ $item->get_short_price($item->coins) }}
								        </span>
                            @endif
                        @else
                            <span class="d-block text-success fw-semibold me-2 me-md-0">
									    Free
								    </span>
                        @endif

                        @if($item->cash == 0 && $item->coins == 0)
                            <span class="d-block text-muted fw-semibold me-2 me-md-0">
									    Offsale
								    </span>
                        @endif

                        @if($item->stock() < $item->stock_limit && $item->special && $item->stock() > 0)
                            <span class="d-block text-danger fw-semibold me-2 me-md-0">
									    {{ $item->stock() }} Remaining
								    </span>
                        @endif

                        @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1)
                            <span class="d-block text-danger fw-semibold me-2 me-md-0">
									    Offsale in {{ $item->offsale_at->diffForHumans(null, true, true) }}
								    </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End item -->
@endforeach
