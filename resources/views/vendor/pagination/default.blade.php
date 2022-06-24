@if ($paginator->hasPages())
<div class="mx-auto mt-3">
    <nav aria-label="Pagination">
        <ul class="pagination flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link">Previous</a>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Previous</a>
            </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item"><a class="page-link">...</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link">Next</a>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif