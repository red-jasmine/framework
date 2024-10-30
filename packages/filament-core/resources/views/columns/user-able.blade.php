<div class="flex flex-col items-center">
    <p class="text-xs font-normal text-gray-800 dark:text-gray-400">{{$getRecord()->{$getName().'_type'} }}</p>
    <p class="text-xs font-normal text-gray-700 dark:text-gray-400">{{$getRecord()->{$getName().'_id'} }}</p>
    @if($getHiddenNickname() === false)
        <p class="text-xs font-normal text-gray-700 dark:text-gray-400">{{$getRecord()->{$getName().'_'.$getNickname()} }}</p>
    @endif
</div>

