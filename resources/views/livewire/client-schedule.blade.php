<div>
    <div class="mt-4 mb-3">

        <input type="date" id="training-date" placeholder="Выберите дату" class="form-control" wire:model="date">
    </div>

    <div class="table-responsive table-card table-nowrap">
        <table class="table align-middle table-hover mb-0">
            <thead>
            <tr>
                <th>Дата и время</th>
                <th>Тренер</th>
                <th>Информация</th>
            </tr>
            </thead>
            <tbody>
            @forelse($trainings as $training)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($training->training_at)->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($training->trainer)
                            <a href="{{ route('users.view', $training->trainer->id) }}">
                                {{ $training->trainer->surname }} {{ $training->trainer->name }} {{ $training->trainer->patronymic }}
                            </a>
                        @else
                            Тренер не найден
                        @endif
                    </td>
                    <td>{{ $training->info ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Нет тренировок</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $trainings->links() }}
    </div>
</div>
