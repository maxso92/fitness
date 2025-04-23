@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item{{ $paginator->onFirstPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1">Пред</a>
            </li>

            @if ($paginator->currentPage() > 3)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            @endif

            @foreach ($paginator->getUrlRange(
                max(1, $paginator->currentPage() - 2),
                min($paginator->lastPage(), $paginator->currentPage() + 2)
            ) as $page => $url)
                <li class="page-item{{ $page == $paginator->currentPage() ? ' active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                </li>
            @endif

            <li class="page-item{{ $paginator->hasMorePages() ? '' : ' disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Вперед</a>
            </li>
        </ul>
    </nav>
@endif
