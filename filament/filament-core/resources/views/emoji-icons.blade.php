@php
    // 使用缓存获取 SVG 文件列表，缓存1小时
    // 直接扫描目录获取文件名，不读取文件内容
    $svgFiles = cache()->remember('emoji-icons-list', 3600, function () {
        $svgPath = base_path('vendor/maiden-voyage-software/blade-emojis/resources/svg');
        $files = [];

        if (is_dir($svgPath)) {
            $scannedFiles = scandir($svgPath);
            foreach ($scannedFiles as $file) {
                // 只获取文件名，不读取文件内容
                // 使用字符串操作检查扩展名，避免任何文件读取
                if (str_ends_with($file, '.svg')) {
                    // 移除 .svg 扩展名，只获取文件名
                    $name = substr($file, 0, -4);
                    // 将文件名转换为组件名：woman-teacher -> emoji-woman-teacher
                    $componentName = str_replace('_', '-', $name);
                    $files[] = [
                        'componentName' => $componentName,
                        'component' => 'emoji-' . $componentName,
                    ];
                }
            }
            // 按名称排序
            usort($files, fn($a, $b) => strcmp($a['componentName'], $b['componentName']));
        }

        return $files;
    });
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold">Emoji 图标库</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                共 {{ count($svgFiles) }} 个图标
            </p>
        </div>

        {{-- 搜索框 --}}
        <div class="w-full max-w-md">
            <input
                type="text"
                id="icon-search"
                placeholder="搜索图标名称..."
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
        </div>

        <div id="icon-grid" class="grid gap-4" style="grid-template-columns: repeat(16, minmax(0, 1fr));">
            @foreach($svgFiles as $icon)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-4 hover:shadow-lg transition-shadow cursor-pointer"
                     data-icon-name="{{ $icon['componentName'] }}"
                     data-component="{{ $icon['component'] }}"
                     title="点击复制: {{ $icon['component'] }}">
                    <div class="flex items-center justify-center">
                        <x-dynamic-component
                            :component="'emoji-' . $icon['componentName']"
                            style="width: 2.5rem; height: 2.5rem;"
                        />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('icon-search');
            const iconGrid = document.getElementById('icon-grid');
            const iconCards = iconGrid.querySelectorAll('[data-icon-name]');

            // 搜索功能
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();

                iconCards.forEach(card => {
                    const iconName = card.getAttribute('data-icon-name').toLowerCase();

                    if (iconName.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // 点击复制功能
            iconCards.forEach(card => {
                card.addEventListener('click', async function() {
                    const componentName = this.getAttribute('data-component');

                    try {
                        await navigator.clipboard.writeText(componentName);

                        // 显示提示
                        const originalTitle = this.getAttribute('title');
                        this.setAttribute('title', '已复制!');
                        this.style.backgroundColor = 'rgba(34, 197, 94, 0.1)';

                        setTimeout(() => {
                            this.setAttribute('title', originalTitle);
                            this.style.backgroundColor = '';
                        }, 1000);
                    } catch (err) {
                        // 降级方案：使用传统方法
                        const textArea = document.createElement('textarea');
                        textArea.value = componentName;
                        textArea.style.position = 'fixed';
                        textArea.style.opacity = '0';
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);

                        const originalTitle = this.getAttribute('title');
                        this.setAttribute('title', '已复制!');
                        this.style.backgroundColor = 'rgba(34, 197, 94, 0.1)';

                        setTimeout(() => {
                            this.setAttribute('title', originalTitle);
                            this.style.backgroundColor = '';
                        }, 1000);
                    }
                });
            });
        });
    </script>
    @endpush
</x-filament-panels::page>

