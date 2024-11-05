@php use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum; @endphp

<div class="flex flex-col items-center">
    <x-filament::badge color="{{$getState()?->getColor()}}" icon="{{$getState()?->getIcon()}}">
        {{$getState()?->getLabel()}}
    </x-filament::badge>

    @if($getState() === OrderStatusEnum::WAIT_SELLER_ACCEPT && $getRecord()->accept_status === \RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum::REJECTED)

        <x-filament::badge color="{{$getRecord()->accept_status?->getColor()}}"
                           icon="{{$getRecord()->accept_status?->getIcon()}}">
            {{$getRecord()->accept_status?->getLabel()}}
        </x-filament::badge>


    @endif
    @if($getState() === OrderStatusEnum::WAIT_SELLER_SEND_GOODS)
        @if($getRecord()->shipping_status === \RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum::PART_SHIPPED)
            <x-filament::badge color="{{$getRecord()->shipping_status?->getColor()}}"
                               icon="{{$getRecord()->shipping_status?->getIcon()}}">
                {{$getRecord()->shipping_status?->getLabel()}}
            </x-filament::badge>
        @endif

    @endif
</div>

