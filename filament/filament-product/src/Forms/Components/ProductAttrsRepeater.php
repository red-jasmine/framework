<?php

namespace RedJasmine\FilamentProduct\Forms\Components;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;


class ProductAttrsRepeater extends Repeater
{

    //protected string $view = 'red-jasmine-filament-product::components.repeater.table';

    protected function setUp() : void
    {
        parent::setUp();
        $this->table([
            Repeater\TableColumn::make('属性项')->width('30%'),
            Repeater\TableColumn::make('属性值')->width('70%'),
        ]);

        $this->defaultItems(0);
        $this->reorderable(false);
        $this->schema([

            Hidden::make('aid')->distinct(),
            Select::make('aid')
                  ->distinct()
                  ->hiddenLabel()
                  ->label(__('red-jasmine-product::product.attrs.aid'))
                  ->live()
                  ->columnSpan(1)
                  ->required()
                  ->disabled(fn($state) => $state)
                  ->options(ProductAttribute::limit(50)->pluck('name', 'id')->toArray())
                  ->searchable()
                  ->getSearchResultsUsing(fn(string $search) : array => ProductAttribute::where('name', 'like',
                      "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                  ->getOptionLabelUsing(fn($value, Get $get) : ?string => ProductAttribute::find($value)?->name)
            ,

            FusedGroup::make([
                Select::make('vids')
                      ->label(__('red-jasmine-product::product.attrs.vid'))
                      ->searchable()
                      ->hiddenLabel()
                      ->multiple(fn(Get $get) => ProductAttribute::find($get('aid'))?->is_allow_multiple)
                      ->required()
                      ->options(fn(Get $get) => ProductAttributeValue::where('aid', $get('aid'))->limit(50)->pluck('name', 'id')->toArray())
                      ->getSearchResultsUsing(fn(string $search) : array => ProductAttributeValue::when($search,
                          function ($query) use ($search) {
                              $query->where('name', 'like', "%{$search}%");
                          })->limit(20)->pluck('name', 'id')->toArray())
                      ->hidden(fn(Get $get) => ProductAttribute::find($get('aid'))?->type === ProductAttributeTypeEnum::TEXT),

                TextInput::make('names')
                         ->maxLength(30)
                         ->hiddenLabel()
                         ->required()
                         ->suffix(fn(Get $get) => ProductAttribute::find($get('aid'))?->unit)
                         ->inlineLabel()
                         ->visible(function (Get $get) {

                             $attr = ProductAttribute::find($get('aid'));

                             return $attr?->type === ProductAttributeTypeEnum::TEXT
                                    && $attr?->is_allow_multiple === false;


                         }),


                TagsInput::make('names')
                    //->view('red-jasmine-filament-product::components.tags-input')
                         ->visible(function (Get $get) {

                        $attr = ProductAttribute::find($get('aid'));
                        return $attr?->type === ProductAttributeTypeEnum::TEXT
                               && $attr?->is_allow_multiple === true;


                    })
                ,


            ])
                      ->hidden(fn(Get $get) => !$get('aid'))
                      ->columns(1),

        ]);

        $this->afterStateHydrated(function ($state, Component $component) {
            if (filled($state)) {
                $newItems = [];
                $aidList  = array_column($state, 'aid');

                $aidModelMaps = ProductAttribute::whereIn('id', $aidList)->get()->keyBy('id');


                foreach ($state as $item) {
                    $newItem = [
                        'aid'   => $item['aid'],
                        'vids'  => null,
                        'names' => null,
                    ];
                    /**
                     * @var $attrModel ProductAttribute
                     */
                    $attrModel = $aidModelMaps[$item['aid']];


                    switch ($attrModel->type) {
                        case ProductAttributeTypeEnum::TEXT:
                            if ($attrModel->is_allow_multiple === false) {
                                $newItem['names'] = array_column($item['values'], 'name')[0] ?? '';
                            } else {
                                $newItem['names'] = array_column($item['values'], 'name');
                            }

                            break;
                        case ProductAttributeTypeEnum::SELECT:
                            if ($attrModel->is_allow_multiple === false) {
                                $newItem['vids'] = array_column($item['values'], 'vid')[0] ?? null;
                            } else {
                                $newItem['vids'] = array_column($item['values'], 'vid');
                            }
                            break;
                    }


                    $newItems[] = $newItem;

                }


                $component->state($newItems);

            }
        });
        // 表单 数据转换为提交数据
        $this->dehydrateStateUsing(function ($state, $component) {
            if (filled($state)) {
                $newItems = [];

                foreach ($state as $item) {
                    $values  = [];
                    $newItem = [
                        'aid'    => $item['aid'],
                        'values' => $values,
                    ];
                    // 判断是否为选择类型
                    if (filled($item['vids'] ?? [])) {
                        if (is_array($item['vids'])) {
                            foreach ($item['vids'] ?? [] as $vid) {
                                $values[] = [
                                    'vid' => $vid,
                                ];
                            }
                        } else {
                            $values[] = [
                                'vid' => $item['vids'],
                            ];
                        }

                    } else {
                        // 输入类型

                        $names = $item['names'] ?? [];
                        if (is_string($names)) {
                            $names = [$names];
                        }

                        foreach ($names as $name) {
                            $values[] = [
                                'vid'  => 0,
                                'name' => $name,
                            ];
                        }

                    }

                    $newItem['values'] = $values;
                    $newItems[]        = $newItem;
                }


                return $newItems;

            }
            return [];
        });
    }
}