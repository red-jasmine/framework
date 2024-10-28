@php use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum; @endphp

<div class="flex flex-col items-center">
    <x-filament::badge color="{{$getState()?->getColor()}}" icon="{{$getState()?->getIcon()}}">
        {{$getState()?->getLabel()}}
    </x-filament::badge>

    @if($getState() === OrderStatusEnum::WAIT_SELLER_ACCEPT)

        <x-filament::badge color="{{$getRecord()->accept_status?->getColor()}}"
                           icon="{{$getRecord()->accept_status?->getIcon()}}">
            {{$getRecord()->accept_status?->getLabel()}}
        </x-filament::badge>


    @endif
</div>

