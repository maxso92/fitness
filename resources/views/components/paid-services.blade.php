<div>
    @props(['ad'])

    @php
        $paidServices = [];
    @endphp

        @foreach ($ad->tariffs as $adTariff)
                @if ($adTariff->end_date >= time())
                        @php
                                $tariff = \App\Models\Tariff::findOrFail($adTariff->tariff_id);
                                switch ($tariff->type) {
                                    case 'Top':
                                        $tariffText = 'Объявление выше всех в списке';
                                        break;
                                    case 'Vip':
                                        $tariffText = 'ВИП размещение';
                                        break;
                                    case 'Colored':
                                        $tariffText = 'Выделение цветом';
                                        break;
                                    case 'Recommended':
                                        $tariffText = 'Размещение в рекомендуемых вакансиях';
                                        break;
                                }
                                $endDate = \Carbon\Carbon::createFromTimestamp($adTariff->end_date)->format('d.m.Y');

                                $cityName = 'Во всех городах';
                                if ($adTariff->city_id != 0) {
                                    $city = \App\Models\City::findOrFail($adTariff->city_id);
                                    $cityName = $city->name;
                                }

                                $categoryName = 'Во всех разделах';
                                if ($adTariff->category_id != 0) {
                                    $category = \App\Models\Category::findOrFail($adTariff->category_id);
                                    $categoryName = $category->name;
                                }

                                $paidServices[] = "Тип: $tariffText (Город: $cityName, Раздел: $categoryName, до $endDate)";
                        @endphp
                @endif
        @endforeach

        @if (!empty($paidServices))
                <b>Примененныe услуги:</b> <br>{!! implode('<br>', $paidServices) !!}
        @else
                <b>Тип:</b> Бесплатное размещение
        @endif

</div>
