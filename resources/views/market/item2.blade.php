@extends('layouts.app')

@section('title', $item->name)

@section('modals')
    <div class="modal" id="confirm-purchase-cash" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                @if(auth()->user()->cash >= $item->cash)
                <h5 class="modal-title">Buy Item</h5>
                <p>
                    Are you sure you want to buy <b>{{ $item->name  }}</b> from {{ $item->owner->username }} for <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> {{ $item->cash }}</span>?
                </p>
                <div class="text-right mt-20">
                    <form method="POST" action="{{ route('market.item.buy', [$item->id, '1']) }}">
                        @csrf
                        <input type="submit" name="submit" class="btn btn-success mr-5" value="Confirm">
                        <a href="#" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
                @elseif(auth()->user()->cash < $item->cash)
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger"></i> Insufficient Funds</h5>
                <p>
                    You need <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> {{ $item->cash - auth()->user()->cash }}</span> to purchase this item.
                </p>
                <div class="text-right mt-20">
                    <a href="#" class="btn btn-success mr-5" role="button">Buy Cash</a>
                    <a href="#" class="btn btn-danger">Cancel</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal" id="confirm-purchase-coins" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                @if(auth()->user()->coins >= $item->coins)
                <h5 class="modal-title">Buy Item</h5>
                <p>
                    Are you sure you want to buy <b>{{ $item->name }}</b> from {{ $item->owner->username }} for <span class="font-weight-semi-bold"><i class="fas fa-coins coin"></i> {{ $item->coins }}</span>?
                </p>
                <div class="text-right mt-20">
                    <form method="POST" action="{{ route('market.item.buy', [$item->id, '2']) }}">
                        @csrf
                        <input type="submit" class="btn btn-success mr-5" value="Confirm">
                        <a href="#" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
                @elseif(auth()->user()->coins < $item->coins)
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger"></i> Insufficient Funds</h5>
                <p>
                    You need <span class="font-weight-semi-bold"><i class="fas fa-coins coin"></i> {{ $item->coins - auth()->user()->coins }}</span> to purchase this item.
                </p>
                <div class="text-right mt-20">
                    <a href="#" class="btn btn-secondary mr-5" role="button">Buy Coins</a>
                    <a href="#" class="btn btn-danger" role="button">Cancel</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal" id="buy" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Buy Item</h5>
                <p>
                    Are you sure you want to buy <b>Tetris' Top Hat #12</b> from Kyle for <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> 1</span>?
                </p>
                <div class="text-right mt-20">
                    <a href="#" class="btn btn-success mr-5" role="button">Confirm</a>
                    <a href="#" class="btn btn-danger" role="button">Cancel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="off-sale" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger"></i> Take Off-sale</h5>
                <p>
                    Are you sure you would like to take <b>Tetris' Top Hat #12</b> off-sale?
                </p>
                <div class="text-right mt-20">
                    <a href="#" class="btn btn-success mr-5" role="button">Confirm</a>
                    <a href="#" class="btn btn-danger" role="button">Cancel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="sell" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Sell <b>Tetris' Top Hat</b></h5>
                <form method="POST">
                    <div class="form-group">
                        <label for="owned-copies" class="required">Owned Copies</label>
                        <select class="form-control" id="owned-copies" required="required">
                            <option value="" disabled="disabled" selected="selected">Select a copy to sell</option>
                            <option value="">#1 of 10000</option>
                            <option value="">#1002 of 10000</option>
                            <option value="">#39 of 10000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cash" class="required">Price</label>
                        <input type="number" id="cash" class="form-control" required="required">
                    </div>
                </form>
                <p>
                    You'll get <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> 1</span>
                </p>
                <div class="text-right mt-20">
                    <a href="#" class="btn btn-success mr-5" role="button">Confirm</a>
                    <a href="#" class="btn btn-danger" role="button">Cancel</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Insert script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <div class="row row-eq-spacing justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 pr-20">
                    <div class="card p-0 m-0">
                        @if(\Carbon\Carbon::createFromTimestamp($item->created_at)->lessThan(\Carbon\Carbon::now()->subMinutes(60)) && !$item->special)<div class="red-ribbon"><span>NEW</span></div>@endif
                        @if($item->special)<div class="blue-ribbon"><span>COLLECTIBLE</span></div>@endif
                        <div class="text-center">
                            <img src="/static/img/avatar.png" class="img-fluid pl-10 pt-10">
                        </div>
                    </div>
                </div>
                <div class="v-spacer hidden-md-and-up"></div>
                <div class="col-md-6">
                    @auth
                                <span class="float-right">
                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" class="edit-ellipsis"><i class="fas fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right w-64">
                                            @if(Auth::user()->id == $item->owner->id)<a href="{{ route('market.item.edit', $item->id) }}" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>@endif
                                            <a href="{{ route('report.item', $item->id) }}" class="dropdown-item"><i class="fas fa-flag"></i> Report</a>
                                        </div>
                                    </div>
                                </span>
                    @endauth
                    <div class="text-truncate inline">
                        <span class="font-size-18 text-truncate">{{ $item->name }}</span>
                        <span class="text-muted font-weight-semi-bold font-size-10 pl-10 text-uppercase">{{ $item->get_type() }}</span>
                    </div>
                    <div class="font-size-12 pb-5">
                        By
                        <a href="{{ route('user.profile', $item->owner->id) }}" class="text-center no-style font-weight-medium">
                            {{ $item->owner->username }}
                        </a>
                        @if($item->owner->id == 1)<span data-toggle="tooltip" data-title="Official Creator" data-placement="bottom"><i class="fas fa-badge-check text-primary"></i></span>@endif
                    </div>
                    @auth
                    @if(!auth()->user()->owns($item) && $item->stock() < $item->stock_limit)
                          @if($item->cash > 0)<a href="#confirm-purchase-cash" class="btn btn-cash" role="button"><i class="fas fa-money-bill"></i> {{ $item->get_short_price($item->cash) }} Cash</a>@endif
                          @if($item->coins > 0)<a href="#confirm-purchase-coins" class="btn btn-coin" role="button"><i class="fas fa-coins"></i> {{ $item->get_short_price($item->coins) }} Coins</a>@endif
                    @endif
                    @endauth
                    @if($item->cash == 0 && $item->coins == 0)<span class="text-danger">Off-sale</span>@endif
                    @if($item->special && $item->stock() >= $item->stock_limit)<span class="text-danger">Out of stock</span>@endif
                    @if($item->special && $item->stock() < $item->stock_limit)<span class="text-danger">{{ $item->stock() }} Remaining</span>@endif

                    <hr class="mt-10">

                    <div class="font-size-12">
                        <span class="text-muted font-weight-semi-bold font-size-10">STATISTICS</span><br>
                        <span class="m-0">Created: {{ Carbon\Carbon::parse($item->created_at)->format('F d, Y') }}</span><br>
                        <span class="m-0">Updated: {{ $item->updated_real->diffForHumans() }}</span><br>
                        <span class="m-0">Sold: {{ $item->get_short_price($item->sold()) }}</span><br>
                        <span class="text-secondary"><a href="#" class="no-style favorite"><i class="far fa-star"></i></a> <b>100</b></span>
                    </div>

                    <div class="font-size-12 pt-5">
                        <span class="text-muted font-weight-semi-bold font-size-10">DESCRIPTION</span>
                        <p class="m-0">{!! nl2br(e($item->desc)) !!}</p>
                    </div>
                    <div class="text-right admin-link font-size-14">
                        <hr>
                        <a href="#" class="font-weight-normal">RE-RENDER</a>
                        •
                        <a href="https://antelope.is/item/{{ $item->id }}" class="font-weight-normal">A.IS</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="v-spacer hidden-md-and-up"></div>
        @if($item->special)
        <div class="col-md-8">
            <div class="card p-0 mt-10">
                <div class="card-header border-bottom">
                    <div class="row">
                        <div class="col-4">
                            <div class="font-size-14 font-weight-medium">Price Chart</div>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                Original Price:
                                <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> {{ $item->get_short_price($item->cash) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                Average Price:
                                <span class="font-weight-semi-bold"><i class="fas fa-money-bill cash"></i> 23</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-10">
                    <canvas id="valueovertime" height="250"  class="chartjs-render-monitor"></canvas>
                    <script>
                        var ctx = document.getElementById('valueovertime').getContext('2d');
                        var myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['4/20', '5/20', '6/20', '7/20', '8/20', '9/20'],
                                datasets: [{
                                    label: "Cash Price",
                                    data: [30, 31, 37, 30, 32, 34],
                                    backgroundColor: [
                                        'rgba(64, 164, 80, 0.5)'
                                    ],
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 26
                                        }
                                    }]
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-0 mt-10">
                <div class="card-header">
                    <div class="row inline">
                        <div class="col-8">
                            <div class="font-size-14 font-weight-medium">Private Sellers</div>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <a href="#sell" class="btn btn-success btn-sm" role="button">SELL</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-0">

                    <div class="text-center border-top p-10">
                        <p>No private sellers found</p>
                    </div>

                    <!-- Start reseller -->
                    <div class="row inline p-10 border-top">
                        <div class="col-8">
                            <div class="inline">
                                <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64">
                                <a href="#" class="no-style font-weight-medium ml-20">Kyle</a>
                                <span class="ml-5">–</span>
                                <span class="text-muted font-size-10 ml-5">#1 of 2,000</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <span class="font-weight-semi-bold mr-20"><i class="fas fa-money-bill cash"></i> 100</span>
                                <a href="#off-sale" class="btn btn-danger btn-sm" role="button">TAKE OFF-SALE</a>
                            </div>
                        </div>
                    </div>
                    <!-- End reseller -->

                    <!-- Start reseller -->
                    <div class="row inline p-10 border-top">
                        <div class="col-8">
                            <div class="inline">
                                <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64">
                                <a href="#" class="no-style font-weight-medium ml-20">Kyle</a>
                                <span class="ml-5">–</span>
                                <span class="text-muted font-size-10 ml-5">#1 of 2,000</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-right">
                                <span class="font-weight-semi-bold mr-20"><i class="fas fa-money-bill cash"></i> 100</span>
                                <a href="#buy" class="btn btn-success btn-sm" role="button">BUY</a>
                            </div>
                        </div>
                    </div>
                    <!-- End reseller -->

                    <div class="p-10 border-top text-center">
                        <button class="btn btn-sm btn-block">LOAD MORE</button>
                    </div>

                </div>
            </div>
        </div>
        @endif

        <div class="col-md-8">
            <div class="card p-0 mt-10">
                <div class="card-header">
                    <div class="font-size-14 font-weight-medium">Comments</div>
                </div>
                <div class="p-0">
                    @auth
                    <div class="border-top p-10">
                        <form method="POST" action="{{ route('market.item.comment', $item->id) }}">
                            @csrf
                            <div class="mb-10">
                                <textarea class="form-control" name="body" id="comment" placeholder="Enter comment..."></textarea>
                            </div>
                            <div class="text-right">
                                <input class="btn btn-primary btn-sm" type="submit" value="Post">
                            </div>
                        </form>
                    </div>
                    @endauth

                        @if($comments != "[]")
                            @foreach($comments as $comment)
                                <!-- Start comment -->
                                    <div class="p-10 border-top">
                                        <div class="comment-box">
                                            <a href="{{ route('user.profile', $comment->owner->id) }}">
                                                <img src="/static/img/headshot.png" class="rounded-circle avatar-bg w-64">
                                            </a>
                                            <div class="comment-text pl-80 pb-8">
                                                <a href="{{ route('report.comment', $comment->id) }}" class="float-right report"><i class="far fa-flag"></i></a>
                                                <span class="font-size-13"><a href="{{ route('user.profile', $comment->owner->id) }}" class="font-weight-semi-bold">{{ $comment->owner->username }}</a></span><span class="text-muted font-size-10 ml-5"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                                <div class="font-size-14 mb-15">{{ $comment->text }}</div>
                                                <div class="text-right admin-link font-size-12">
                                                    <a href="https://antelope.is/comment/{{ $comment->id }}/delete" class="font-weight-normal">DELETE</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End comment -->
                            @endforeach
                            {{ $comments->links('vendor.pagination.default') }}
                            <br>
                        @else
                            <div class="text-center border-top p-10">
                                <p>No comments found</p>
                            </div>
                        @endif

                </div>
            </div>
        </div>
    </div>
@endsection
