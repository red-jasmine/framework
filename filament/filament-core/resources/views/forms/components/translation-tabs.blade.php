@php
    $afterHeader = $getChildSchema($schemaComponent::AFTER_HEADER_SCHEMA_KEY)?->toHtmlString();
    $isAside = $isAside();
    $isCollapsed = $isCollapsed();
    $isCollapsible = $isCollapsible();
    $isCompact = $isCompact();
    $isContained = $isContained();
    $isDivided = $isDivided();
    $isFormBefore = $isFormBefore();
    $description = $getDescription();
    $footer = $getChildSchema($schemaComponent::FOOTER_SCHEMA_KEY)?->toHtmlString();
    $heading = $getHeading();
    $headingTag = $getHeadingTag();
    $icon = $getIcon();
    $iconColor = $getIconColor();
    $iconSize = $getIconSize();
    $shouldPersistCollapsed = $shouldPersistCollapsed();
    $isSecondary = $isSecondary();
    $id = $getId();

    $isTranslatable = $isTranslatable();
    if($isTranslatable){
        $repeater = $getChildSchema($schemaComponent::REPEATER_SCHEMA_KEY)->getComponents()[0];
        $items = $repeater->getItems();

        $addAction = $repeater->getAction($repeater->getAddActionName());
        $addActionAlignment =  $repeater->getAddActionAlignment();
        $isAddable = $repeater->isAddable();
    }

@endphp

<div
        {{
            $attributes
                ->merge([
                    'id' => $id,
                ], escape: false)
                ->merge($getExtraAttributes(), escape: false)
                ->merge($getExtraAlpineAttributes(), escape: false)
                ->class(['fi-sc-section'])
        }}

        x-data="{
            activeTab: 'default',
        }"
>
    @if($isTranslatable)
        <x-filament::tabs
                :contained="true"
                :vertical="false"
        >
            <x-filament::tabs.item
                    alpine-active="activeTab ==='default'"
                    @click="activeTab ='default'"
            >
                默认
            </x-filament::tabs.item>

            @foreach ($items as $itemKey => $item)
                @php
                    $keyJs = is_string($itemKey) ? "'" . addslashes($itemKey) . "'" : (string)$itemKey;
                    $alpineActive = "activeTab === {$keyJs}";
                    $alpineClick = "activeTab = {$keyJs}";
                    $deleteActionForItem = $repeater->getAction($repeater->getDeleteActionName())(['item' => $itemKey]);
                    $isDeletable = $repeater->isDeletable();
                @endphp

                <x-filament::tabs.item
                        :alpine-active="$alpineActive"
                        :x-on:click="$alpineClick"
                >
                    {{ $repeater->getItemLabel($itemKey) }}
                    @if ($isDeletable && $deleteActionForItem->isVisible())
                        <span x-on:click.stop class="ml-1">
                            {{ $deleteActionForItem }}
                        </span>
                    @endif
                </x-filament::tabs.item>
                {{-- 添加翻译按钮 --}}
            @endforeach
            @if ($isAddable && $addAction->isVisible())
                <div
                        @class([
                            'fi-tabs-item',
                            ($addActionAlignment instanceof Alignment) ? ('fi-align-' . $addActionAlignment->value) : $addActionAlignment,
                        ])
                >
                    {{ $addAction }}
                </div>
            @endif


        </x-filament::tabs>
    @endif
    {{-- Tab 内容 --}}
    <div >
        <div
                x-show="activeTab ==='default'"
        >
            {{ $getChildSchema()->gap(! $isDivided)->extraAttributes(['class' => 'fi-section-content']) }}

        </div>
        @if($isTranslatable)
            @foreach ($items as $itemKey => $item)
                @php
                    $keyJs = is_string($itemKey) ? "'" . addslashes($itemKey) . "'" : (string)$itemKey;
                    $showExpression = "activeTab === {$keyJs}";
                    $childSchema = $repeater->getChildSchema($itemKey);
                @endphp
                <div
                        x-show="{{ $showExpression }}"
                        x-cloak
                        wire:key="{{ $childSchema->getLivewireKey() }}.item"
                        wire:ignore.self
                >
                    {{ $childSchema }}
                </div>
            @endforeach
        @endif
    </div>


</div>
