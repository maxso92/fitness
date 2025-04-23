<div>



    <div class="table-responsive table-card table-nowrap">
        <table class="table align-middle table-hover mb-0">
        <thead>
        <tr>
            <th>Время входа</th>
            <th>IP адрес</th>


        </tr>
        </thead>
        <tbody>
        @if($authHistory->isEmpty())
            <tr>
                <td colspan="5">Нет записей</td>
            </tr>
        @else
            @foreach($authHistory as $history)
                <tr>
                    <td>  {{ $history->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ $history->ip_address }}
                        {{ $history->browser }} ({{ $history->device }})</td>


                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    @if(!$authHistory->isEmpty())
        {{ $authHistory->links() }}
        <p>Всего записей: {{ $authHistory->total() }}</p>
    @endif
</div>
</div>
