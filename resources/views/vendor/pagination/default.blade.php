@if ($paginator->hasPages())
    <div class="text-center">
        <div class="btn-group" role="group" aria-label="Button group as pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-square" type="button" disabled>
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous page</span>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="no-style">
                    <button class="btn btn-square" type="button">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                            <span class="sr-only">Previous page</span>
                    </button>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn btn-square" type="button">...</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn btn-square btn-primary" type="button">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="no-style"><button class="btn btn-square" type="button">{{ $page }}</button></a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="no-style">
                    <button class="btn btn-square" type="button">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <span class="sr-only">Next page</span>
                    </button>
                </a>
            @else
                <button class="btn btn-square" type="button" disabled>
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Next page</span>
                </button>
            @endif
        </div>
    </div>
@endif
