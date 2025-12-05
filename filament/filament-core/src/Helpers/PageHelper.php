<?php

namespace RedJasmine\FilamentCore\Helpers;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Components\ViewComponent;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use RedJasmine\Support\Domain\Data\UserData;

/**
 * @property static $translationNamespace
 */
trait PageHelper
{


    public static function ownerQueryUsing(string $name = 'owner') : callable
    {
        return static fn($query, Get $get) => $query->onlyOwner(UserData::from([
            'type' => $get('owner_type'), 'id' => $get('owner_id')
        ]));
    }

    public static function translationLabels(ViewComponent $component, array $parent = []) : ViewComponent
    {
        // 设置自身的字段
        if (property_exists($component, 'label') && !$component->isSetLabel()) {
            $component->label(static function (ViewComponent $component) use ($parent) {
                /**
                 * @var Field $component
                 */
                $name = $component->getName();

                if (method_exists($component, 'childComponents') && count($component->getDefaultChildComponents()) > 0) {
                    return __(static::$translationNamespace.'.relations.'.$name);
                }

                if (filled($parent)) {
                    $name = implode('.', $parent).'.'.$name;
                }
                return __(static::$translationNamespace.'.fields.'.$name);
            });
        }


        if (method_exists($component, 'getComponents')) {
            foreach ($component->getComponents() as $childComponent) {
                static::translationLabels($childComponent);
            }
            return $component;
        }

        if (method_exists($component, 'childComponents')) {
            $parent[] = $component->getName();
            foreach ($component->getChildComponents() as $childComponent) {
                static::translationLabels($childComponent, $parent);
            }
            return $component;
        }
        if ($component instanceof Table) {
            // 字段翻译
            foreach ($component->getColumns() as $column) {

                if (!$column->isSetLabel()) {
                    $column->label(static function (Column $column) {
                        return __(static::$translationNamespace.'.fields.'.$column->getName());
                    });
                }
            }
            // 如何获取列分组

            // 过滤条件翻译

            /**
             * @var$filter  Tables\Filters\BaseFilter
             */
            foreach ($component->getFilters() as $filter) {

                if (!$filter->isSetLabel()) {
                    $filter->label(static function (BaseFilter $filter) {
                        return __(static::$translationNamespace.'.fields.'.$filter->getName());
                    });
                }

            }


        }
        return $component;

    }


    public static function ownerTableColumns(string $name = 'owner') : array
    {
        // 定义 组件
        return [
            // Tables\Columns\TextColumn::make($name)
            //                          ->formatStateUsing(fn($state) => $state?->getNickname())
            //                          ->label(__('red-jasmine-support::support.owner'))
            //                          ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make($name.'_type')
                      ->label(__('red-jasmine-support::support.owner_type'))
                      ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make($name.'_id')
                      ->label(__('red-jasmine-support::support.owner_id'))
                      ->copyable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }

}
