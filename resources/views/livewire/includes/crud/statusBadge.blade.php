@switch($field)
    @case('active')
        <span class="badge bg-success fs-6">Активен</span>
        @break
    @case('inactive')
        <span class="badge bg-danger fs-6">Не активен</span>
        @break
    @default
        {{ $field}}
@endswitch
