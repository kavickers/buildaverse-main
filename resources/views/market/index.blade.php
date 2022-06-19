<x-app-layout>
    <x-slot name="title">Marketplace</x-slot>
    <x-slot name="navigation"></x-slot>

    <div class="card p-3 px-4 mb-2 mt-md-2 mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="dropdown shrink me-4">
                <button class="text-xl bg-transparent border-0 p-0 text-white" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    Advanced Sorting<i class="bi bi-chevron-down text-sm ms-2 align-middle"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <span class="text-center dropdown-item-text notification-dropdown-title p-0">Advanced Sorting</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider mb-1" />
                    </li>
                    <span class="dropdown-item-text text-sm text-muted text-bold" href="#">PRICING</span>
                    <li>
                        <a class="dropdown-item" href="#">Low to High (&uarr;)</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">High to Low (&darr;)</a>
                    </li>
                    <span class="dropdown-item-text text-sm text-muted text-bold" href="#">MISCELLANEOUS</span>
                    <li><a class="dropdown-item" href="#">Top Rated</a></li>
                    <li><a class="dropdown-item" href="#">Most Bought</a></li>
                    <li><a class="dropdown-item" href="#">On Most Wishlists</a></li>
                </ul>
            </div>
            <div class="input-parent has-icon">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search..." />
            </div>
        </div>
    </div>
    <div class="row mt-1 mt-md-2 mb-4">
        <div class="col-md-9">
            <ul class="nav nav-pills nav-rounded mt-3 mt-md-0 mb-1 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link @if(!isset($_GET['sort'])) active @endif" aria-current="page" href="{{ route('market.index') }}">Featured</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "hats") active @endif" href="@if(isset($_GET['advanced'])) ?sort=hats&advanced={{ $_GET['advanced'] }} @else ?sort=hats @endif">Hats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "faces") active @endif" href="@if(isset($_GET['advanced'])) ?sort=faces&advanced={{ $_GET['advanced'] }} @else ?sort=faces @endif">Faces</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "accessories") active @endif" href="@if(isset($_GET['advanced'])) ?sort=accessories&advanced={{ $_GET['advanced'] }} @else ?sort=accessories @endif">Accessories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "shirts") active @endif" href="@if(isset($_GET['advanced'])) ?sort=shirts&advanced={{ $_GET['advanced'] }} @else ?sort=shirts @endif">Shirts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "tshirts") active @endif" href="@if(isset($_GET['advanced'])) ?sort=tshirts&advanced={{ $_GET['advanced'] }} @else ?sort=tshirts @endif">T-Shirts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(isset($_GET['sort']) && $_GET['sort'] == "pants") active @endif" href="@if(isset($_GET['advanced'])) ?sort=pants&advanced={{ $_GET['advanced'] }} @else ?sort=pants @endif">Pants</a>
                </li>
            </ul>
        </div>
        <div class="col-md-3 text-end">
            <a href="#" class="btn btn-primary btn-rounded d-none d-md-inline-block" style="margin-top: 2px">Create Item</a>
            <a href="#" class="btn btn-primary btn-floating d-flex d-md-none">
                <i class="bi bi-plus"></i>
            </a>
        </div>
    </div>
    <div class="row" id="market-data">
        @include('components.load_market')
    </div>
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
                        $("#market-data").append(data.html);
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
    </x-slot>
</x-app-layout>
