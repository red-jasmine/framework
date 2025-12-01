@php
    use Filament\Support\Enums\Alignment;

    $fieldWrapperView = $getFieldWrapperView();

    $items = $getItems();

    $addAction = $getAction($getAddActionName());
    $addActionAlignment = $getAddActionAlignment();
    $deleteAction = $getAction($getDeleteActionName());

    $isAddable = $isAddable();
    $isDeletable = $isDeletable();

    $key = $getKey();
    $statePath = $getStatePath();

    $rawState = $getRawState() ?? [];
    $itemKeys = array_keys($rawState);

    // // 获取当前活跃的 tab（基于第一个有 locale 的项）
    $activeTabKey = null;
    $activeTabKey = $activeTabKey ?? ($itemKeys[0] ?? null);
@endphp

<x-dynamic-component :component="$fieldWrapperView" :field="$field">
    <div
            {{
                $attributes
                    ->merge($getExtraAttributes(), escape: false)
                    ->class([
                        'fi-fo-repeater',
                        'fi-translatable-tabs',
                    ])
            }}
            x-data="translatableTabs({
            activeTab: @js($activeTabKey),
        })"
    >
        {{-- Tabs 导航 --}}
        <x-filament::tabs
                :contained="true"
                :vertical="false"
        >
            {{-- 动态生成 Tab 项 --}}
            @foreach ($items as $itemKey => $item)
                @php
                    $itemLabel = $getItemLabel($itemKey);
                    $keyJs = is_string($itemKey) ? "'" . addslashes($itemKey) . "'" : (string)$itemKey;
                    $alpineActive = "activeTab === {$keyJs}";
                    $alpineClick = "activeTab = {$keyJs}";
                    $deleteActionForItem = $deleteAction(['item' => $itemKey]);
                @endphp
                <x-filament::tabs.item
                        :x-bind:alpine-active="$alpineActive"
                        :x-on:click="$alpineClick"
                >
                    <span>{{ $itemLabel }}</span>
                    @if ($isDeletable && $deleteActionForItem->isVisible())
                        <span x-on:click.stop class="ml-2">
                            {{ $deleteActionForItem }}
                        </span>
                    @endif
                </x-filament::tabs.item>
            @endforeach

            {{-- 添加翻译按钮 --}}
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

        {{-- Tab 内容 --}}
        <div class="mt-4">
            @foreach ($items as $itemKey => $item)
                @php
                    $keyJs = is_string($itemKey) ? "'" . addslashes($itemKey) . "'" : (string)$itemKey;
                    $showExpression = "activeTab === {$keyJs}";
                    $childSchema = $getChildSchema($itemKey);
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
        </div>
    </div>
</x-dynamic-component>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('translatableTabs', (config) => ({
                activeTab: config.activeTab !== null ? String(config.activeTab) : null,

                init() {
                    const statePath = @js($statePath);
                    let previousKeys = [];
                    let isInitialized = false;

                    // 初始化 previousKeys
                    this.$nextTick(() => {
                        const state = this.$wire.get(statePath) || {};
                        previousKeys = Object.keys(state).map(k => String(k));
                        isInitialized = true;

                        // 如果没有活跃的 tab，切换到第一个
                        if (previousKeys.length > 0 && !this.activeTab) {
                            this.activeTab = previousKeys[0];
                        }
                    });

                    // 监听 Livewire 更新
                    this.$watch(`$wire.${statePath}`, (newState) => {
                        if (!isInitialized) return;

                        this.$nextTick(() => {
                            const state = this.$wire.get(statePath) || {};
                            const keys = Object.keys(state).map(k => String(k));

                            // 检查是否有新添加的项
                            const newKeys = keys.filter(k => !previousKeys.includes(k));

                            if (newKeys.length > 0) {
                                // 如果有新添加的项，切换到最后一个新添加的项
                                this.activeTab = newKeys[newKeys.length - 1];
                            }

                            // 更新 previousKeys
                            previousKeys = keys;
                        });
                    }, {deep: true, immediate: false});
                }
            }));
        });
    </script>
@endpush
