<style>
    a.link::before {
        content: '';
        display: inline-block;
        padding: 10px;
    }
</style>
@if ($paginator->hasPages())
    <ul class="articles__paginator paginator">
        @if ($paginator->onFirstPage())
            <li class="paginator__button paginator__button-left"></li>
        @else
            <li class="paginator__button paginator__button-left">
                <a href="{{ $paginator->previousPageUrl() }}" class="link"></a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="paginator__item">...</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="paginator__item paginator__item-active">{{ $page }}</li>
                    @else
                        <li class="paginator__item">
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="paginator__button paginator__button-active">
                <a href="{{ $paginator->nextPageUrl() }}" class="link"></a>
            </li>
        @else
            <li class="paginator__button"></li>
        @endif
    </ul>
@endif
