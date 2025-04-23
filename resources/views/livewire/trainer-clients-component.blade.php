<div>
    <div class="mt-4 mb-3">
        <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Поиск по ФИО или email...">
    </div>

    <div class="table-responsive table-card table-nowrap">
        <table class="table align-middle table-hover mb-0">
            <thead>
            <tr>
                <th>ФИО</th>
                <th>Email</th>
                <th>Дата рождения</th>
            </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>
                        <a href="{{ route('users.view', $client->id) }}" data-tippy-content="Перейти в профиль">
                            {{ $client->surname }} {{ $client->name }} {{ $client->patronymic }}
                        </a>
                    </td>
                    <td>{{ $client->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($client->birthday)->format('d.m.Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Нет клиентов</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $clients->links() }}
    </div>
</div>
