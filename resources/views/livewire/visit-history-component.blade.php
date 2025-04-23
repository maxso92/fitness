
    <div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Дата и время входа в зал</th>

                </tr>
                </thead>
                <tbody>
                @forelse($visits as $visit)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($visit->created_at)->format('d.m.Y H:i:s') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Нет данных о посещениях</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="mt-3">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
