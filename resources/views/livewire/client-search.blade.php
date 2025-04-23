<div>
    <input type="text" wire:model="search" class="form-control" placeholder="Поиск клиента по ФИО" />

    <select name="clients[]" class="form-control" multiple required>
        @foreach($clients as $client)
            <option value="{{ $client->id }}">
                {{ $client->surname }} {{ $client->name }} {{ $client->patronymic }}
            </option>
        @endforeach
    </select>
</div>
