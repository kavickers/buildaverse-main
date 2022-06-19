@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
    <div class="row row-eq-spacing">
        <div class="col-md-12">
            <h3 class="text-white font-weight-semi-bold">Marketplace</h3>
        </div>
        <div class="col-md-3">

            <a href="/market/create" class="no-style">
                <div class="card create-button">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16"><i class="fas fa-plus-circle mr-5"></i> Create</p>
                </div>
            </a>

            <h5 class="section-div">
                Categories
            </h5>

            <a href="/market" class="no-style">
                <div class="card market-button @if(!isset($_GET['sort'])) selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16"><i class="fas fa-star mr-5"></i> Featured</p>
                </div>
            </a>

            <a href="?sort=hats" class="no-style">
                <div class="card market-button @if(isset($_GET['sort']) && $_GET['sort'] == "hats") selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16 text-white"><i class="fas fa-hard-hat mr-5"></i> Hats</p>
                </div>
            </a>

            <a href="?sort=accessories" class="no-style">
                <div class="card market-button @if(isset($_GET['sort']) && $_GET['sort'] == "accessories") selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16 text-white"><i class="fas fa-tools mr-5"></i> Accessories</p>
                </div>
            </a>

            <a href="?sort=faces" class="no-style">
                <div class="card market-button @if(isset($_GET['sort']) && $_GET['sort'] == "faces") selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16 text-white"><i class="fas fa-smile mr-5"></i> Faces</p>
                </div>
            </a>

            <a href="?sort=shirts" class="no-style">
                <div class="card market-button @if(isset($_GET['sort']) && $_GET['sort'] == "shirts") selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16 text-white"><i class="fas fa-tshirt mr-5"></i> Shirts</p>
                </div>
            </a>

            <a href="?sort=pants" class="no-style">
                <div class="card market-button @if(isset($_GET['sort']) && $_GET['sort'] == "pants") selected @endif">
                    <p class="font-weight-semi-bold p-10 m-10 font-size-16 text-white"><i class="fas fa-socks mr-5"></i> Pants</p>
                </div>
            </a>

        </div>
        <div class="v-spacer hidden-md-and-up"></div>
        <div class="col-md-9">
            <div class="card p-10 m-0 mr-10">
                <form action="#" method="POST">
                    <div class="form-row row-eq-spacing-sm m-0">
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search marketplace..." required="required">
                                <div class="input-group-append">
                                    <button class="btn" type="submit">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span class="sr-only">Search marketplace...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="area-of-specialization" required="required">
                                <option value="" selected="selected" disabled="disabled">Advanced Sort</option>
                                <option value="1">Recently Updated</option>
                                <option value="2">Newest First</option>
                                <option value="3">Oldest First</option>
                                <option value="4">Least Expensive First</option>
                                <option value="5">Most Expensive First</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row row-eq-spacing p-0 mt-10">
                    @foreach($items as $item)
                        <div class="col-6 col-sm-3 p-0 pr-10 pb-10">
                            <div class="card p-0">
                                @if(\Carbon\Carbon::createFromTimestamp($item->created_at)->lessThan(\Carbon\Carbon::now()->subMinutes(60)) && !$item->special)<div class="red-ribbon"><span>NEW</span></div>@endif
                                @if($item->special)<div class="blue-ribbon"><span>COLLECTIBLE</span></div>@endif
                                <div class="text-center">
                                    @if($item->approved == 1)
                                        <img src="/static/img/avatar.png" class="img-fluid">
                                    @elseif($item->approved == 2)
                                        <img src="/static/img/avatar.png" class="img-fluid">
                                    @else
                                        <img src="/static/img/avatar.png" class="img-fluid">
                                    @endif
                                </div>
                                <div class="item-container">
                                    <p class="m-0 text-truncate">
                                        <a href="{{ route('market.item', $item->id) }}">{!! nl2br(e($item->name)) !!}</a>
                                    </p>
                                    <p class="m-0 font-size-12 text-truncate">
                                        By <a href="{{ route('user.profile', $item->creator_id) }}">{{ $item->owner->username }}</a>
                                    </p>
                                    @if($item->cash == 0 && $item->coins == 0)<div class="text-danger">Offsale</div>@endif
                                    @if(!$item->free()) @if($item->cash > 0) <span class="cash mr-10"><i class="fas fa-money-bill"></i> {{ $item->get_short_price($item->cash) }}</span> @endif @if($item->coins > 0) <span class="coin"><i class="fas fa-coins"></i> {{ $item->get_short_price($item->coins) }}</span> @endif @else <div class="text-success">Free</div>@endif
                                    @if($item->offsale_at != null && $item->special == 0 && !$item->offsale_at->isPast() && $item->cash > 0 && $item->coins > 0)<div class="text-secondary text-truncate"><i class="fas fa-clock"></i> Offsale in {{ $item->offsale_at->diffForHumans(null, true, true) }}</div>@endif
                                    @if($item->stock() < $item->stock_limit && $item->special && $item->stock() > 0)<div class="text-danger text-truncate"><i class="fas fa-exclamation-circle"></i> {{ $item->stock() }} Remaining</div>@endif
                                    @if(!$item->free() && $item->cash == 0 && $item->coins == 0 || $item->special && $item->stock() >= $item->stock_limit  || $item->stock() == 0 && $item->offsale_at == null || !$item->special && $item->offsale_at == null || !$item->special && $item->offsale_at->isPast())
                                        <div><br></div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
        </div>
            <div class="float-center"> {{ $items->withQueryString()->links('vendor.pagination.default') }} </div>
    </div>
@endsection
