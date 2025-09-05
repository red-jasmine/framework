<x-filament-panels::page>


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
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @endif


</x-filament-panels::page>
