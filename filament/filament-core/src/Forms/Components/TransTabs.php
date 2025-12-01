<?php

namespace RedJasmine\FilamentCore\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

class TransTabs extends Section
{

    protected string $view          = 'red-jasmine-filament-core::forms.components.section';
    protected array  $localeOptions = [];


    protected bool|Closure $isTranslatable = true;


    /**
     * @param  bool|Closure  $condition
     *
     * @return $this
     */
    public function translatable(bool|Closure $condition = true) : static
    {
        $this->isTranslatable = $condition;
        return $this;
    }

    public function isTranslatable() : bool
    {
        return $this->evaluate($this->isTranslatable);
    }

    /**
     * 设置语言选项的显示标签
     *
     * @param  array<string, string>  $options  语言代码 => 显示标签的映射数组
     *
     * @return $this
     */
    public function localeOptions(array $options) : static
    {
        $this->localeOptions = $options;


        return $this;
    }

    public function buildRepeater(array|Closure $components)
    {
        $newComponents = [];
        foreach ($components as $component) {
            $newComponents[] = clone $component;
        }
        // // 多语言翻译
        $availableLocales = $this->localeOptions ?: $this->getDefaultLocaleOptions();

        $repeater = Repeater::make('translations')
                            ->relationship('translations')
                            ->view('red-jasmine-filament-product::forms.components.translatable-tabs')
                            ->dehydrated()
                            ->saveRelationshipsUsing(null)
                            ->label('多语言翻译')
                            ->inlineLabel(false)
                            ->schema([...$newComponents, Hidden::make('locale')])
                            ->itemLabel(fn(array $state) : ?string => $availableLocales[$state['locale']] ?? $state['locale'] ?? '新翻译');

        // 自定义添加按钮，弹出语言选择下拉框
        $repeater->addAction(function (Action $action, Get $get, Set $set) {
            // 获取已存在的语言列表
            $existingLocales = [];
            $translations    = $get('translations') ?? [];
            if (is_array($translations)) {
                foreach ($translations as $translation) {
                    if (is_array($translation) && isset($translation['locale'])) {
                        $existingLocales[] = $translation['locale'];
                    }
                }
            }

            // 获取可用的语言选项
            $availableLocales = $this->localeOptions ?: $this->getDefaultLocaleOptions();

            // 过滤掉已存在的语言
            $filteredLocales = [];
            foreach ($availableLocales as $locale => $label) {
                if (!in_array($locale, $existingLocales)) {
                    $filteredLocales[$locale] = $label;
                }
            }

            $action->icon(Heroicon::Plus)
                   ->label('添加翻译')
                   ->disabled(empty($filteredLocales))
                   ->schema([
                       Select::make('locale')
                             ->label('选择语言')
                             ->required()
                             ->options($filteredLocales)
                             ->searchable()
                             ->native(false)
                             ->prefixIcon('heroicon-o-globe-alt')
                             ->helperText(empty($filteredLocales) ? '所有语言已添加' : '请选择要添加的语言'),
                   ])
                   ->action(function (array $data) use ($get, $set) : void {
                       $locale = $data['locale'] ?? null;

                       if ($locale) {
                           // 检查是否已存在该语言
                           $translations = $get('translations') ?? [];
                           if (!is_array($translations)) {
                               $translations = [];
                           }

                           // 检查是否已存在
                           $exists = false;
                           foreach ($translations as $translation) {
                               if (is_array($translation) && isset($translation['locale']) && $translation['locale'] === $locale) {
                                   $exists = true;
                                   break;
                               }
                           }

                           if (!$exists) {
                               // 创建新的翻译项，设置 locale 字段
                               $newTranslation = ['locale' => $locale];
                               $translations[] = $newTranslation;
                               $set('translations', $translations, shouldCallUpdatedHooks: true);
                           }
                       }
                   });
        });

        return $repeater;
    }

    /**
     * 获取默认的语言选项
     *
     * @return array<string, string>
     */
    protected function getDefaultLocaleOptions() : array
    {
        return [
            'zh-CN' => '简体中文',
            'en-US' => 'English (US)',
            'en-GB' => 'English (UK)',
            'ja-JP' => '日本語',
            'ko-KR' => '한국어',
            'de-DE' => 'Deutsch',
            'fr-FR' => 'Français',
            'es-ES' => 'Español',
        ];
    }

    public function schema(array|Closure $components) : static
    {
        $repeaterComponents = $components;
        $addRepeaterComponents = [];
        if($this->isTranslatable()){
            $addRepeaterComponents[]  = $this->buildRepeater($repeaterComponents);
        }

        parent::schema([...$components, ...$addRepeaterComponents]);
        return $this;
    }

}
