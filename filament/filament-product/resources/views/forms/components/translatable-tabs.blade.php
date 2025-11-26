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
                      $itemData = $rawState[$itemKey] ?? [];
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
                    // 监听 Livewire 更新，自动切换到新添加的项
                    const statePath = @js($statePath);
                    this.$watch(`$wire.${statePath}`, () => {
                        this.$nextTick(() => {
                            // 获取最新的状态
                            const state = this.$wire.get(statePath) || {};
                            const keys = Object.keys(state).map(k => String(k));

                            // 如果添加了新项，切换到最后一个
                            if (keys.length > 0 && !keys.includes(String(this.activeTab))) {
                                this.activeTab = keys[keys.length - 1];
                            } else if (keys.length > 0 && !this.activeTab) {
                                this.activeTab = keys[0];
                            }
                        });
                    }, {deep: true});
                }
            }));
        });
    </script>
@endpush
