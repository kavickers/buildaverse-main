<x-app-layout>
    <x-slot name="title">{{ $item->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card card-body mb-4">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="position-relative overflow-hidden rounded">
                    @if($item->special) <div class="collectible-badge"></div> @endif
                    @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && (($item->cash > 0 && $item->coins > 0) || ($item->coins < 0 && $item->cash < 0))) <div class="timed-badge">
                </div> @endif
                <img src="{{ $item->get_render() }}" class="img-fluid p-2 item-preview @if($item->special) is-collectible @endif @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && (($item->cash > 0 && $item->coins > 0) || ($item->coins < 0 && $item->cash < 0))) is-timed @endif" />
            </div>
        </div>
        <div class="col-md-7">
            <div class="d-flex">
                <div class="min-w-0 w-100">
                    <span class="text-muted fw-bolder text-sm text-uppercase">{{ $item->get_type() }}</span>
                    <div class="text-4xl fw-bold mb-1">
                        {{ $item->name }}
                    </div>
                    <div class="text-muted fw-semibold">
                        By:
                        <a href="{{ route('user.profile', $item->owner->id) }}" class="fw-semibold @if($item->owner->power > 0) text-danger @endif">{{ $item->owner->username }}</a>
                    </div>
                    <div class="mt-3">
                        @auth
                        @if(!auth()->user()->owns($item) && $item->stock() > 0)
                            @if($item->cash > 0)
                            <button type="button" data-bs-toggle="modal" data-bs-target="#cashModal" class="btn btn-success d-none d-lg-inline-block btn-lg fw-semibold">
                                <i class="bi bi-cash-stack text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->cash) }} Cash
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#cashModal" class="btn btn-success d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                <i class="bi bi-cash-stack text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->cash) }} Cash
                            </button>

                            <!-- cash modal -->
                            <div class="modal fade" id="cashModal" tabindex="-1" aria-labelledby="cashModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        @if(auth()->user()->cash >= $item->cash)
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="cashModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to buy
                                            <span class="fw-semibold">{{ $item->name }}</span>
                                            for
                                            <span class="text-success fw-semibold"><i class="bi bi-cash-stack align-middle text-lg me-1"></i>{{ number_format($item->cash) }}</span>?
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('market.item.buy', [$item->id, '1']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    Buy Now
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="cashModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            You need
                                            <span class="text-success fw-semibold"><i class="bi bi-cash-stack align-middle text-lg me-1"></i>{{ $item->cash - auth()->user()->cash }}</span> to purchase this item.
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="button" class="btn btn-success">
                                                Buy Cash
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- end cash modal -->
                            @endif
                            @if($item->coins > 0)
                            <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal" class="btn btn-warning d-none d-lg-inline-block btn-lg fw-semibold">
                                <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal" class="btn btn-warning d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                            </button>

                            <!-- coins modal -->
                            <div class="modal fade" id="coinModal" tabindex="-1" aria-labelledby="coinsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        @if(auth()->user()->coins >= $item->coins)
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="coinsModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to buy
                                            <span class="fw-semibold">{{ $item->name }}</span>
                                            for
                                            <span class="text-warning fw-semibold"><i class="bi bi-coin align-middle text-lg me-1"></i>{{ number_format($item->coins) }}</span>?
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('market.item.buy', [$item->id, '2']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">
                                                    Buy Now
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="coinsModalLabel">
                                                Insufficient Funds
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            You need
                                            <span class="text-warning fw-semibold"><i class="bi bi-coin align-middle text-lg me-1"></i>{{ $item->coins - auth()->user()->coins }}</span> to purchase this item.
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="button" class="btn btn-warning">
                                                Buy Coins
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- end coins modal -->
                            @endif
                            @if($item->free())
                            <button type="button" data-bs-toggle="modal" data-bs-target="#freeModal" class="btn btn-info d-none d-lg-inline-block btn-lg fw-semibold">
                                Free
                            </button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#freeModal" class="btn btn-info d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                Free
                            </button>

                            <!-- free modal -->
                            <div class="modal fade" id="freeModal" tabindex="-1" aria-labelledby="freeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="freeModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to buy
                                            <span class="fw-semibold">{{ $item->name }}</span>
                                            for
                                            <span class="text-info fw-semibold">free</span>?
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('market.item.buy', [$item->id, '3']) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-info">
                                                    Buy Now
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end free modal -->
                            @endif
                        @endif
                        @endauth

                        @if($item->cash == 0 && $item->coins == 0)<span class="d-block mt-md-2 ms-2 text-danger align-middle">Offsale</span>@endif
                        @if($item->special && $item->stock() >= $item->stock_limit)<span class="d-block mt-md-2 ms-2 text-danger align-middle">Out of stock</span>@endif
                        @if($item->special && $item->stock() < $item->stock_limit)<span class="d-block mt-md-2 ms-2 text-danger align-middle">{{ $item->stock() }} Remaining</span>@endif
                        @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && (($item->cash > 0 && $item->coins > 0) || ($item->coins < 0 && $item->cash < 0)))<span class="d-block mt-md-2 ms-2 text-danger align-middle">Offsale in {{ $item->offsale_at->diffForHumans(null, true, true) }}</span>@endif
                    </div>
                </div>
                @auth
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
                        @if($item->owner->id == auth()->user()->id)
                        <li>
                            <a class="dropdown-item" href="{{ route('market.item.edit', $item->id) }}">Edit</a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('report.item', $item->id) }}">Report</a>
                        </li>
                        @if($item->special > 0 && auth()->user()->owns($item))
                        <li>
                            <a class="dropdown-item" role="button" data-bs-toggle="modal" data-bs-target="#sellModal">Sell</a>
                        </li>
                        @endif
                        @if(auth()->user()->owns($item) && $item->special == 0 && $item->owner->id != auth()->user()->id)
                        <li>
                            <a class="dropdown-item" role="button" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete from Inventory</a>
                        </li>
                        @endif
                    </ul>
                </div>

                @if(auth()->user()->owns($item) && $item->special == 0 && $item->owner->id != auth()->user()->id)
                <!-- begin delete modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h4 class="modal-title" id="deleteModalLabel">
                                    Delete From Inventory
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete
                                <span class="fw-semibold">{{ $item->name }}</span>
                                from your inventory? This action cannot be reversed.
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <form method="POST" action="{{ route('market.item.delete', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end delete modal -->
                @endif

                @if($item->special > 0 && auth()->user()->owns($item))
                <!-- begin collectible sell modal -->
                <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="collectibleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h4 class="modal-title" id="collectibleModalLabel">
                                    Sell Collectible
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('market.list', $item->id) }}">
                                @csrf
                                <div class="modal-body">
                                    <label class="text-muted text-uppercase text-sm fw-bold mb-1">WHICH SERIAL TO SELL?</label>
                                    <select class="form-control mb-2" name="serial">
                                        @foreach(auth()->user()->specials() as $special)
                                        @if(!$special->onsale())
                                        <option value="{{ $special->id }}">Serial #{{ $special->collection_number }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <label class="text-muted text-uppercase text-sm fw-bold mb-1">PRICE</label>
                                    <div class="input-parent has-icon">
                                        <i class="bi bi-cash-stack text-success"></i>
                                        <input type="number" name="price" class="form-control" placeholder="Price" />
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Sell Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end collectible modal -->
                @endif
                @endauth
            </div>
            <hr />
            <div class="text-sm text-muted fw-bold text-uppercase">
                STATISITCS
            </div>
            <div><span class="fw-semibold">Created:</span> {{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</div>
            <div><span class="fw-semibold">Updated:</span> {{ $item->updated_real->diffForHumans() }}</div>
            <div><span class="fw-semibold">Sold:</span> {{ $item->get_short_price($item->sold()) }}</div>
            <div class="text-sm text-muted fw-bold text-uppercase mt-3">
                DESCRIPTION
            </div>
            <div>
                @if($item->desc != null)
                {!! nl2br(e($item->desc)) !!}
                @else
                No description set.
                @endif
            </div>
        </div>
    </div>
    </div>

    @if($item->special > 0)
    <!-- begin price chart -->
    <h3>Price Chart</h3>
    <div class="card card-body mb-4"></div>
    <!-- end price chart -->

    <!-- begin private listings -->
    <h3>Private Listings</h3>
    <div class="card card-body mb-4">
        @foreach($markets as $reseller)
        <div class="section">
            <div class="d-flex gap-3 align-items-center">
                <img src="/img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                <div class="auto">
                    <a href="{{ route('user.profile', $reseller->seller->id) }}" class="text-xl fw-semibold text-light">{{ $reseller->seller->username }}</a>
                    <div class="text-muted text-sm">Serial #{{ $reseller->inventory->collection_number }}</div>
                </div>
                <div>
                    @if(auth()->user()->id != $reseller->user_id)
                        <button class="btn btn-success shrink px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#resaleModal{{ $reseller->id }}">
                            <i class="bi bi-cash-stack text-xl align-middl me-2 lh-1"></i>{{ number_format($reseller->price) }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('market.unlist', $item->id) }}">
                            @csrf
                            <input hidden name="serial" value="{{ $reseller->id }}" />
                            <button class="btn btn-danger shrink px-4 fw-semibold">
                                Take Offsale
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->id != $reseller->user_id)
        <!-- resale modal -->
        <div class="modal fade" id="resaleModal{{ $reseller->id }}" tabindex="-1" aria-labelledby="{{ $reseller->id }}resaleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    @if(auth()->user()->cash >= $reseller->price)
                    <div class="modal-header border-bottom-0 pb-0">
                        <h4 class="modal-title" id="{{ $reseller->id }}resaleModalLabel">
                            Confirm Purchase
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to buy
                        <span class="fw-semibold">{{ $item->name }}</span>
                        from
                        <span class="fw-semibold">{{ $reseller->seller->username }}</span>
                        for
                        <span class="text-success fw-semibold"><i class="bi bi-cash-stack align-middle text-lg me-1"></i>{{ number_format($reseller->price) }}</span>?
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form method="POST" action="{{ route('market.listing.buy', [$item->id, $reseller->id]) }}">
                            @csrf
                            <input hidden name="serial" value="{{ $reseller->id }}" />
                            <button type="submit" class="btn btn-success">
                                Buy Now
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="modal-header border-bottom-0 pb-0">
                        <h4 class="modal-title" id="{{ $reseller->id }}resaleModalLabel">
                            Confirm Purchase
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        You need
                        <span class="text-success fw-semibold"><i class="bi bi-cash-stack align-middle text-lg me-1"></i>{{ $reseller->price - auth()->user()->cash }}</span> to purchase this item.
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-success">
                            Buy Cash
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- end resale modal -->
        @endif
        @endforeach
        {{ $markets->links('vendor.pagination.default') }}
    </div>
    <!-- end private listings -->
    @endif

    <h3>Comments</h3>
    <div class="card card-body">
        @auth
        <div class="d-flex gap-2">
            <form class="w-100" action="{{ route('market.item.comment', $item->id) }}" method="POST">
                @csrf
                <textarea type="text" name="body" class="form-control w-100" placeholder="What are your thoughts about this item?" rows="3"></textarea>
                <button type="submit" class="btn btn-success mt-2">Post</button>
            </form>
        </div>
        <hr class="mb-3" />
        @endauth
        <span id="comment-data">

            @if($comments->count() > 0)
            @include('components.load_item_comments')
            @else
            <center class="mt-4">No comments :(</center>
            @endif
        </span>
    </div>
    <div class="mb-5">&nbsp;</div>
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
                        $("#comment-data").append(data.html);
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