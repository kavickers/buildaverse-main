<x-app-layout>
    <x-slot name="title">Money</x-slot>
    <x-slot name="navigation"></x-slot>
    <h2>Money</h2>
    <div class="card p-3 mb-3">
        <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="transactions" data-bs-toggle="pill" data-bs-target="#transactions-tab" type="button" role="tab" aria-controls="transactions" aria-selected="true">
                    Transactions
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exchange-currency" data-bs-toggle="pill" data-bs-target="#exchange-currency-tab" type="button" role="tab" aria-controls="exchange-currency" aria-selected="false">
                    Exchange Currency
                </button>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane show active" id="transactions-tab" role="tabpanel" aria-labelledby="transactions" tabindex="0">
            <div class="card overflow-hidden">
                <div class="text-2xl my-2 py-1 px-3 px-md-4 fw-semibold">
                    Transactions
                </div>
                <hr class="d-block d-md-none mt-0 mb-2 mx-3" />
                <div class="bg-primary-800 border-top border-bottom border-primary text-primary-200 py-2 px-4 d-none d-md-block mb-2">
                    <div class="row opacity-75 fw-semibold text-sm text-uppercase">
                        <div class="col-md-2">Date</div>
                        <div class="col-md-3">Asset</div>
                        <div class="col-md-2 text-center">Member</div>
                        <div class="col-md-3">Type</div>
                        <div class="col-md-2">Amount</div>
                    </div>
                </div>
                <div class="py-2 px-3">
                    <span id="trans-data">
                        @if($transactions->count() > 0)
                            @include('components.load_user_transactions')
                        @else
                            <div class="mb-2 text-center">No transactions :(</div>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="exchange-currency-tab" role="tabpanel" aria-labelledby="exchange-currency" tabindex="0">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="text-2xl fw-semibold mb-1">
                        Transfer Cash to Coins
                    </div>
                    <div class="card p-3">
                        <form method="POST" action="{{ route('user.trade.cash') }}">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        AMOUNT IN CASH
                                    </div>
                                    <div class="input-parent has-icon">
                                        <i class="text-success bi bi-cash-stack"></i>
                                        <input type="number" name="cash" class="form-control" id="transfer_cash" step="1" onKeyUp="calcCash();" placeholder="Cash" />
                                    </div>
                                </div>
                                <div class="col-md-2 text-center text-muted">
                                    <i class="bi bi-arrow-left-right text-2xl lh-1 d-none d-md-block"></i>
                                    <i class="bi bi-arrow-down-up text-2xl lh-1 d-block d-md-none my-3"></i>
                                </div>
                                <div class="col-md-5">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        AMOUNT IN COINS
                                    </div>
                                    <div class="input-parent has-icon">
                                        <i class="text-warning bi bi-coin"></i>
                                        <input type="number" class="form-control" id="coins_result" step="1" placeholder="Coins" disabled readonly />
                                    </div>
                                </div>
                            </div>
                            <i class="d-block text-sm text-muted mt-1">* Amount must be divisible by 10</i>
                            <hr />
                            <div class="text-end">
                                <button class="btn btn-primary">Transfer</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-2xl fw-semibold mb-1">
                        Transfer Coins to Cash
                    </div>
                    <div class="card p-3">
                        <form method="POST" action="{{ route('user.trade.coins') }}">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        AMOUNT IN COINS
                                    </div>
                                    <div class="input-parent has-icon">
                                        <i class="text-warning bi bi-coin"></i>
                                        <input type="number" name="coins" class="form-control" id="transfer_coins" step="1" onKeyUp="calcCoins();" placeholder="Coins" />
                                    </div>
                                </div>
                                <div class="col-md-2 text-center text-muted">
                                    <i class="bi bi-arrow-left-right text-2xl lh-1 d-none d-md-block"></i>
                                    <i class="bi bi-arrow-down-up text-2xl lh-1 d-block d-md-none my-3"></i>
                                </div>
                                <div class="col-md-5">
                                    <div class="text-xs fw-bold text-muted mb-1">
                                        AMOUNT IN CASH
                                    </div>
                                    <div class="input-parent has-icon">
                                        <i class="text-success bi bi-cash-stack"></i>
                                        <input type="number" class="form-control" id="cash_result" step="1" placeholder="Cash" disabled readonly />
                                    </div>
                                </div>
                            </div>
                            <i class="d-block text-sm text-muted mt-1">* Amount must be divisible by 10</i>
                            <hr />
                            <div class="text-end">
                                <button class="btn btn-primary">Transfer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2">&nbsp;</div>
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
                        $("#trans-data").append(data.html);
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
        </script>
        <script>
            function calcCash() {
                value = document.getElementById("transfer_cash").value;
                result1 = value * 10;
                if(result1 % 10 === 0) {
                    document.getElementById("coins_result").value = result1;
                }
            }
            function calcCoins() {
                value = document.getElementById("transfer_coins").value;
                if(value % 10 === 0) {
                    result = value / 10;
                    document.getElementById("cash_result").value = result;
                }
            }
        </script>
    </x-slot>
</x-app-layout>