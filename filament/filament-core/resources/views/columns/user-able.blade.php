<div class="flex flex-col items-center px-2">
    @if($isShowType())
        <p class="text-xs font-normal text-gray-800 dark:text-gray-400">{{$getRecord()->{$getName().'_type'} }}</p>
    @endif
    <p class="text-xs font-normal text-gray-800 dark:text-gray-400">
        {{--        TODO--}}
        <x-filament::link color="info" tooltip="{{ $getRecord()->{$getName().'_type'} }}">
            @if($getHiddenNickname() === false && filled($getRecord()->{$getName().'_'.$getNickname()}??null))
                <span>{{$getRecord()->{$getName().'_'.$getNickname()} }}</span> |
            @endif
            <span> {{$getRecord()->{$getName().'_id'} }}</span>
        </x-filament::link>
    </p>
</div>

