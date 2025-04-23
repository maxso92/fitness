

    <div class="col-12 col-sm-6 col-md-4 col-lg-3 card-sub-menu" >
        <div class="card px-3 py-4"  style="color:#FFF;">

            <div class="d-flex justify-content-between">
                <span><b> С возращением,  {{ auth()->user()->name }}</b> </span>

            </div>

            <hr  class="hr-menu">
            <a class="ad-menu side-menu" href="{{ route('users.profile') }}">
                <i class="bi bi-person"></i>
                Мой профиль
            </a>
            <div class="d-flex justify-content-between  side-menu">
                <span>Статус:</span>
                <b>Работодатель</b>
            </div>
            <div class="d-flex justify-content-between  side-menu">
                <span>Номер аккаунта:</span>
                <b> {{ auth()->id() }}</b>
            </div>
            <hr  class="hr-menu">
            <div class="d-flex justify-content-between  side-menu">
                <span>Ваш баланс:</span>
                <b>{{ auth()->user()->balance }} руб.</b>
            </div>

            <form method="POST" class="mb-2" action="{{ route('deposit.post') }}">
                @csrf
                <div class="d-flex">
                    <input type="text" class="form_deposit appearance-none block bg-white text-gray-700 border border-gray-200 rounded  leading-tight focus:outline-none focus:bg-white " name="sum" required>
                    <button type="submit" class="btn btn-menu ml-2">Пополнить</button>
                </div>
            </form>

            <img src="{{asset('site/img/index/payments.jpeg')}}" class="payments">

            <hr class="hr-menu">
            <a href="{{ route('ads.create') }}" style="font-size: 13.5px;" class="btn btn-menu mb-1">Разместить вакансию бесплатно</a>
            <a href="{{ route('ads.create') }}"   style="font-size: 13.5px;"  class="btn btn-outline-ads mb-2 ">Разместить вакансию платно</a>


            @php
                $userId = auth()->id();
                $AdsCount = App\Models\Ad::where('user_id', $userId)->count();

                $activeAdsCount = App\Models\Ad::where('status', 1)->where('user_id', $userId)->count();
                $awaitingModerationCount = App\Models\Ad::where('status', 0)->where('user_id', $userId)->count();
                $archivedAdsCount = App\Models\Ad::where('status', 2)->where('user_id', $userId)->count();
                $blockAdsCount = App\Models\Ad::where('status', 2)->where('user_id', $userId)->count();
                $messageCount = App\Models\MessageChat::where('is_read', 0)->where('user_id', $userId)->count();

            @endphp


            <div class="d-flex justify-content-between">

                <a class="ad-menu side-menu"   href="{{route('dashboard')}}"  >
                    <i class="bi bi-bookmarks-fill" id="toggleIcon"></i>
                    Мои объявления ({{$activeAdsCount}})
                </a>


                <a class="ad-menu side-menu" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <b style="margin-top: 5px !important;"><i class="bi bi-caret-left" id="caretIcon"></i></b>
                </a>
            </div>


            <div class="collapse" id="collapseExample">
                <div class="card card-body">

                    <div class="d-flex justify-content-between  side-menu">
                        <span>Активные:</span>
                        <b>{{$activeAdsCount}} </b>
                    </div>
                    <div class="d-flex justify-content-between  side-menu">
                        <span>На проверке:</span>
                        <b>{{$awaitingModerationCount}} </b>
                    </div>
                    <div class="d-flex justify-content-between  side-menu">
                        <span>Отклонены:</span>
                        <b>{{$blockAdsCount}} </b>
                    </div>
                    <div class="d-flex justify-content-between  side-menu">
                        <span>В архиве: </span>
                        <b>{{$archivedAdsCount}} </b>
                    </div>

                </div>
            </div>

            <hr class="hr-menu">
            <span class="badge badge-primary">Переписка</span>
            <a class="ad-menu side-menu" href="{{ route('message.create') }}">
                <i class="bi bi-envelope  "></i>
                Новое сообщение
            </a>
            <a style="color:#FF8FA2;" class="ad-menu side-menu {{ $messageCount > 0 ? ' ' : '' }}" href="{{ route('messages') }}">
                <i class="bi bi-envelope{{ $messageCount > 0 ? '-fill' : '' }}"></i>
                Переписка ({{$messageCount}})
            </a>

            @if($messageCount > 0)
                <div class="ad-menu side-menu {{ $messageCount > 0 ? 'new-messages' : '' }}" >
                    Новых сообщений ({{$messageCount}})
                </div>
            @endif

            <hr class="hr-menu">
            <a class="ad-menu side-menu" href="{{ route('transaction') }}">
                <i class="bi bi-list-ul"></i>
                История операций
            </a>

            <a href="{{ route('export.ads') }}" class="ad-menu side-menu">
                <i class="bi bi-cloud-download"></i>
                Экспорт объявлений
            </a>

            <a href="{{ route('import.view') }}" class="ad-menu side-menu">
                <i class="bi bi-cloud-upload"></i>
                Импорт объявлений
            </a>
        </div>



        <script>
            var collapseElement = document.getElementById('collapseExample')
            var caretIconElement = document.getElementById('caretIcon')

            // Listen for the show.bs.collapse event
            collapseElement.addEventListener('show.bs.collapse', function () {
                caretIconElement.classList.replace('bi-caret-left', 'bi-caret-down') // replace with your collapse icon
            })

            // Listen for the hide.bs.collapse event
            collapseElement.addEventListener('hide.bs.collapse', function () {
                caretIconElement.classList.replace('bi-caret-down', 'bi-caret-left') // replace with your expand icon
            })
        </script>
    </div>






