@if ($paginator->hasPages())

    <div class="d-flex justify-content-between align-items-center   mx-2 py-3">

        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link"><i class=" bi-arrow-left-short"></i></span>
                </li>
            @else
                <li class="paginate_button page-item previous">
                    <button type="button" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" aria-label="@lang('pagination.previous')"><i class=" bi-arrow-left-short"></i></button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max($currentPage - 2, 1);
                $end = min($currentPage + 2, $lastPage);
            @endphp

            {{-- First Page Link --}}
            @if ($start > 1)
                <li class="paginate_button page-item">
                    <button type="button" class="page-link" wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')">1</button>
                </li>
                @if ($start > 2)
                    <li class="paginate_button page-item disabled"><span class="page-link">...</span></li>
                @endif
            @endif

            {{-- Page Links --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <li class="paginate_button page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="paginate_button page-item">
                        <button type="button" class="page-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</button>
                    </li>
                @endif
            @endfor

            {{-- Last Page Link --}}
            @if ($end < $lastPage)
                @if ($end + 1 < $lastPage)
                    <li class="paginate_button page-item disabled"><span class="page-link">...</span></li>
                @endif
                <li class="paginate_button page-item">
                    <button type="button" class="page-link" wire:click="gotoPage({{ $lastPage }}, '{{ $paginator->getPageName() }}')">{{ $lastPage }}</button>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="paginate_button page-item next">
                    <button type="button" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" aria-label="@lang('pagination.next')"><i class=" bi-arrow-right-short"></i></button>
                </li>
            @else
                <li class="paginate_button page-item next disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link"><i class=" bi-arrow-right-short"></i></span>
                </li>
            @endif
        </ul>
    </div>
@endif
