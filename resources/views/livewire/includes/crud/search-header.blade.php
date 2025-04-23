
    <div class="content pt-3 px-3 px-lg-6">
        <div class="card">

            <button id="search-toggle" class="btn btn-md btn"
                    wire:click="$toggle('searchFormVisible')">
                @if($searchFormVisible)
                    Скрыть поиск
                @else
                    Открыть поиск
                @endif
                @if(!$searchFormVisible)
                    <span id="search-count" class="badge rounded-pill bg-success">{{ $searchCriteriaCount }}</span>
                @endif
            </button>
            <form class="card-body" id="search-form"
                  wire:submit.prevent="search"
                  style="display: {{ $searchFormVisible ? 'block' : 'none' }}">





