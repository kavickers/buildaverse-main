<x-app-layout>
    <x-slot name="title">{{ $item->name }}</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card card-body mb-4">
        <div class="row">
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="position-relative overflow-hidden rounded">
                    @if($item->special) <div class="collectible-badge"></div> @endif
                    @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1) <div class="timed-badge"></div> @endif
                    <img src="{{ $item->get_render() }}" class="img-fluid item-preview @if($item->special) is-collectible @endif @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1) is-timed @endif" />
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
                                @if(!auth()->user()->owns($item) && $item->stock() < $item->stock_limit)
                                    @if($item->cash > 0)
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#cashModal"
                                                class="btn btn-success d-none d-lg-inline-block btn-lg fw-semibold">
                                            <i class="bi bi-cash-stack text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->cash) }} Cash
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#cashModal"
                                                class="btn btn-success d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                            <i class="bi bi-cash-stack text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->cash) }} Cash
                                        </button>
                                    @endif
                                    @if($item->coins > 0)
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal"
                                                class="btn btn-warning d-none d-lg-inline-block btn-lg fw-semibold">
                                            <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal"
                                                class="btn btn-warning d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                            <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                                        </button>
                                    @endif
                                    @if($item->free())
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal"
                                                    class="btn btn-warning d-none d-lg-inline-block btn-lg fw-semibold">
                                            <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                                        </button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#coinModal"
                                                    class="btn btn-warning d-block w-100 d-lg-none text-center btn-lg fw-semibold mb-2">
                                            <i class="bi bi-coin text-2xl align-middle me-2 lh-1"></i>{{ $item->get_short_price($item->coins) }} Coins
                                        </button>
                                    @endif
                                @endif
                            @endauth
                            <!-- modal -->
                            <div class="modal fade" id="cashModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="exampleModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to buy
                                            <span class="fw-semibold">Crystalline Faberge Egg</span>
                                            for
                                            <span class="text-success fw-semibold"><i
                                                    class="bi bi-cash-stack align-middle text-lg me-1"></i>
													10,000</span>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="button" class="btn btn-success">
                                                Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->

                            <!-- modal -->
                            <div class="modal fade" id="coinModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h4 class="modal-title" id="exampleModalLabel">
                                                Confirm Purchase
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to buy
                                            <span class="fw-semibold">Crystalline Faberge Egg</span>
                                            for
                                            <span class="text-warning fw-semibold"><i
                                                    class="bi bi-coin align-middle text-lg me-1"></i>
													10,000</span>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="button" class="btn btn-warning">
                                                Buy Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal -->
                            @if($item->cash == 0 && $item->coins == 0)<span class="d-block mt-md-2 ms-2 text-danger align-middle">Offsale</span>@endif
                            @if($item->special && $item->stock() >= $item->stock_limit)<span class="d-block mt-md-2 ms-2 text-danger align-middle">Out of stock</span>@endif
                            @if($item->special && $item->stock() < $item->stock_limit)<span class="d-block mt-md-2 ms-2 text-danger align-middle">{{ $item->stock() }} Remaining</span>@endif
                            @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 || $item->cash == -1 && $item->coins > 0 || $item->coins == -1)<span class="d-block mt-md-2 ms-2 text-danger align-middle">Offsale in {{ $item->offsale_at->diffForHumans(null, true, true) }}</span>@endif
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="text-xl bg-transparent border-0 p-0 text-light" type="button"
                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-xl"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li>
									<span
                                        class="text-center dropdown-item-text notification-dropdown-title p-0">More</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider mb-1" />
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#">Report</a>
                            </li>
                            <li>
                                <a class="dropdown-item" role="button" data-bs-toggle="modal"
                                   data-bs-target="#sellModal">Sell</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Delete from Inventory</a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h4 class="modal-title" id="exampleModalLabel">
                                        Sell Collectible
                                    </h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="text-muted text-uppercase text-sm fw-bold mb-1">WHICH SERIAL TO
                                        SELL?</label>
                                    <select class="form-control mb-2">
                                        <option value="1">Serial #1</option>
                                        <option value="2">Serial #2</option>
                                        <option value="3">Serial #3</option>
                                        <option value="4">Serial #4</option>
                                    </select>
                                    <label class="text-muted text-uppercase text-sm fw-bold mb-1">PRICE IN
                                        CASH</label>
                                    <div class="input-parent has-icon">
                                        <i class="bi bi-cash-stack text-success"></i>
                                        <input type="text" class="form-control" placeholder="Price (Cash)" />
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-success">
                                        Sell Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="text-sm text-muted fw-bold text-uppercase">
                    STATISITCS
                </div>
                <div><span class="fw-semibold">Created:</span> June 10th, 2022</div>
                <div><span class="fw-semibold">Updated:</span> 2 hours ago</div>
                <div><span class="fw-semibold">Sold:</span> 12</div>
                <div class="text-sm text-muted fw-bold text-uppercase mt-3">
                    DESCRIPTION
                </div>
                <div>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
            </div>
        </div>
    </div>
    <h3>Price Chart</h3>
    <div class="card card-body mb-4"></div>
    <h3>Private Listings</h3>
    <div class="card card-body mb-4">
        <div class="section">
            <div class="d-flex gap-3 align-items-center">
                <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                <div class="auto">
                    <a href="#" class="text-xl fw-semibold text-light">Kyle</a>
                    <div class="text-muted text-sm">Serial #54 out of #87</div>
                </div>
                <div>
                    <button class="btn btn-success shrink px-4 fw-semibold" data-bs-toggle="modal"
                            data-bs-target="#cashModal">
                        <i class="bi bi-cash-stack text-xl align-middl me-2 lh-1"></i>10,200 Cash
                    </button>
                </div>
            </div>
        </div>
    </div>
    <h3>Comments</h3>
    <div class="card card-body">
        <div class="d-flex gap-2">
            <input type="text" class="form-control" placeholder="What are your thoughts about this item?" />
            <button type="submit" class="btn btn-success px-3">Post</button>
        </div>
        <hr class="mb-0" />
        <div class="section">
            <div class="d-flex gap-3 align-items-center">
                <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="86" />
                <div class="w-100">
                    <a href="#" class="text-xl fw-semibold text-light">Kyle</a>
                    <div class="text-muted mb-2">
                        this item looks so nice but i was too lazy to participate in the
                        egg hunt so i didn't get it... sucks to suck!
                    </div>
                    <div class="text-muted text-sm">Posted 8 minutes ago</div>
                </div>
                <div class="dropdown">
                    <button class="text-xl bg-transparent border-0 p-0 text-light" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
                        this item looks so nice but i was too lazy to participate in the
                        egg hunt so i didn't get it... sucks to suck!
                    </div>
                    <div class="text-muted text-sm">Posted 8 minutes ago</div>
                </div>
                <div class="dropdown">
                    <button class="text-xl bg-transparent border-0 p-0 text-light" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
                        this item looks so nice but i was too lazy to participate in the
                        egg hunt so i didn't get it... sucks to suck!
                    </div>
                    <div class="text-muted text-sm">Posted 8 minutes ago</div>
                </div>
                <div class="dropdown">
                    <button class="text-xl bg-transparent border-0 p-0 text-light" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
</x-app-layout>
