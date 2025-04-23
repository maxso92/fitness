<div class="content pb-0 pt-3 px-3 px-lg-6 d-flex flex-column-fluid">
    <div class="container-fluid px-0">

        <div class="d-flex mb-4 align-items-center">
            <div class="flex-grow-1 border-top border-gray"></div>
            <h5 class="flex-shrink-0 mb-0 px-3">Статистика клуба</h5>
            <div class="flex-grow-1 border-top border-gray"></div>
        </div>


        <div class="mb-4 text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary {{ $timePeriod === 'day' ? 'active' : '' }}"
                        wire:click="changeTimePeriod('day')">День</button>
                <button type="button" class="btn btn-outline-primary {{ $timePeriod === 'month' ? 'active' : '' }}"
                        wire:click="changeTimePeriod('month')">Месяц</button>
                <button type="button" class="btn btn-outline-primary {{ $timePeriod === 'quarter' ? 'active' : '' }}"
                        wire:click="changeTimePeriod('quarter')">Квартал</button>
                <button type="button" class="btn btn-outline-primary {{ $timePeriod === 'year' ? 'active' : '' }}"
                        wire:click="changeTimePeriod('year')">Год</button>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body border-0 bg-primary-subtle mb-2">
                    <div class="d-flex flex-column text-center justify-content-center py-2">
                        <div>
                            <h5 class="fs-3 mb-2">{{ number_format($totalClients, 0, ',', ' ') }}</h5>
                            <p class="mb-1">Всего клиентов</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body border-0 bg-success-subtle mb-2">
                    <div class="d-flex flex-column text-center justify-content-center py-2">
                        <div>
                            <h5 class="fs-3 mb-2">{{ number_format($totalSubscriptions, 0, ',', ' ') }}</h5>
                            <p class="mb-1">Куплено абонементов</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body border-0 bg-info-subtle mb-2">
                    <div class="d-flex flex-column text-center justify-content-center py-2">
                        <div>
                            <h5 class="fs-3 mb-2">{{ number_format($activeSubscriptions, 0, ',', ' ') }}</h5>
                            <p class="mb-1">Активных абонементов</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body border-0 bg-warning-subtle mb-2">
                    <div class="d-flex flex-column text-center justify-content-center py-2">
                        <div>
                            <h5 class="fs-3 mb-2">{{ number_format($trainingsCount, 0, ',', ' ') }}</h5>
                            <p class="mb-1">Проведено тренировок</p>
                            <span class="text-body-tertiary d-block small">
                                @switch($timePeriod)
                                    @case('day') за сегодня @break
                                    @case('month') за месяц @break
                                    @case('quarter') за квартал @break
                                    @case('year') за год @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
