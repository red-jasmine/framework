@php use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum; @endphp

<div class="flex flex-col items-center">
    <p class="text-xs font-normal text-gray-700 dark:text-gray-400">{{$getRecord()->seller_type}}</p>
    <p class="text-xs font-normal text-gray-700 dark:text-gray-400">{{$getRecord()->seller_id}}</p>
    <p class="text-xs font-normal text-gray-800 dark:text-gray-400">{{$getRecord()->seller_nickname}}</p>

</div>

