<div>

<header class="">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Города
        </h2>
    </div>
</header>

<div class="py-3">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl  rounded-mdsm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <a href="{{ route('city.create') }}" class="btn btn-md btn-primary">
                    Создать
                </a>


                <form class="mt-4 mb-4" wire:submit.prevent="search" >
                    <div class="input-group mb-3">
                        <input type="text"  wire:model.defer="search"   class="form-control appearance-none   bg-white text-gray-700 border border-gray-900 rounded mr-3 leading-tight focus:outline-none focus:bg-white border-form-blue border-form-blue" placeholder="Поиск...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit">Найти</button>
                        </div>
                    </div>
                </form>

                    <table class="table-auto w-full">
                        <thead>
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Наименование</th>
                            <th class="px-4 py-2">Страна</th>

                            <th class="px-4 py-2">Объявления</th>
                            <th class="px-4 py-2">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cities as $city)
                            <tr>
                                <td class="border px-4 py-2">{{ $city->id }}</td>
                                <td class="border px-4 py-2"><a class="ad-href" href="{{ route('city.edit', $city->id) }}">{{ $city->name }}</a></td>
                                      @php
                            $adsCount = \App\Models\Ad::where('city_id', '=', $city->id)->count();
                            $adsActiveCount = \App\Models\Ad::where('city_id', '=', $city->id)->where('status', '=', 1)->count();
                            $country = \App\Models\Country::where('id', '=', $city->country_id)->first();


                                      @endphp

                                <td class="border px-4 py-2"> {{ $country ? $country->name : '' }}</td>
                                <td class="border px-4 py-2"> {{ $adsCount }} / {{$adsActiveCount}}</td>



                                <td class="border px-4 py-2">
                                    <button wire:click="confirmUserDeletion({{ $city->id }})" class="btn btn-sm btn-danger">
                                        Удалить
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{ $cities->links() }}

                    <!-- Delete Confirmation Modal -->
                    @if ($confirmingUserDeletion)
                    <x-dialog-modal wire:model="confirmingUserDeletion">
                        <x-slot name="title">
                            {{ __('Удалить запись') }}
                        </x-slot>

                        <x-slot name="content">
                            {{ __('Вы уверены, что хотите удалить запись?') }}

                        </x-slot>
                        <x-slot name="footer">
                            <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                                {{ __('Отменить') }}
                            </x-secondary-button>

                            <x-danger-button class="ml-3" wire:click="deleteUser" wire:loading.attr="disabled">
                                {{ __('Удалить запись') }}
                            </x-danger-button>
                        </x-slot>
                    </x-dialog-modal>
                @endif




            </div>

         </div>
    </div>
</div>


</div>




