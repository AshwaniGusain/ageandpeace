@if ($paginator->hasPages())
    <ul class="nav">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="nav-item disabled">
                <span class="nav-link btn btn-outline-primary btn-pagination btn-prev disabled">
                    @svg('assets/svg/icon_back_arrow.svg', 'icon')
                    <span class="sr-only">Previous Page</span>
                </span>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link btn btn-outline-primary btn-pagination" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    @svg('assets/svg/icon_back_arrow.svg', 'icon')
                    <span class="sr-only">Previous Page</span>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="nav-item disabled"><span class="nav-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="nav-item "><span class="nav-link btn btn-outline-primary btn-pagination active">{{ $page }}</span></li>
                    @else
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary btn-pagination" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="nav-item"><a class="nav-link btn btn-outline-primary btn-pagination" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    @svg('assets/svg/icon_arrow.svg', 'icon')
                    <span class="sr-only">Next Page</span>
                </a></li>
        @else
            <li class="nav-item"><span class="nav-link btn btn-outline-primary btn-pagination disabled">
                @svg('assets/svg/icon_arrow.svg', 'icon')
                    <span class="sr-only">Next Page</span>
                </span></li>
        @endif
    </ul>
@endif
