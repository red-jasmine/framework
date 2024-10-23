<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @endif

    @foreach($forms as $form)
        <x-filament-panels::form
                :wire:key="$this->getId() . '.forms.'.$form"
                :wire:submit="$form.'Submit'"
        >
            {{ $this->{$form} }}

            <x-filament-panels::form.actions
                    :actions="$this->getCachedFormActions()"
                    :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>


    @endforeach




</x-filament-panels::page>
