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
