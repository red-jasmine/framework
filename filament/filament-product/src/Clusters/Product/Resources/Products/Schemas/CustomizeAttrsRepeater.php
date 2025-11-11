<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;


class CustomizeAttrsRepeater extends Repeater
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->table([
            Repeater\TableColumn::make(__('red-jasmine-product::product-attribute.labels.product-attribute'))->width('30%'),
            Repeater\TableColumn::make(__('red-jasmine-product::product-attribute-value.labels.product-attribute-value'))->width('70%'),
        ]);

        $this->defaultItems(0);
        $this->reorderable(false);
        $this->schema([


            TextInput::make('name')
                     ->required()
                     ->label(__('red-jasmine-product::product-attribute.labels.product-attribute'))
                     ->distinct(),
            TextInput::make('values')
                     ->label(__('red-jasmine-product::product-attribute-value.labels.product-attribute-value'))
                     ->maxLength(200)
                     ->columnSpanFull()
                     ->required()


        ]);

        $this->afterStateHydrated(function ($state, Component $component) {
            if (filled($state)) {
                $newItems = [];


                foreach ($state as $item) {
                    $newItem = [
                        'aid'    => 0,
                        'name'   => $item['name'] ?? '',
                        'values' => array_column($item['values'], 'name')[0] ?? '',
                    ];


                    $newItems[] = $newItem;

                }

                $component->state($newItems);

            }
        });
        // 表单 数据转换为提交数据
        $this->dehydrateStateUsing(function ($state) {
            if (filled($state)) {
                $newItems = [];
                foreach ($state as $item) {

                    $newItem = [
                        'aid'    => 0,
                        'name'   => $item['name'],
                        'values' => [
                            [
                                'vid'  => 0,
                                'name' => $item['values'] ?? null,
                            ]
                        ],
                    ];

                    $newItems[] = $newItem;
                }
                return $newItems;

            }
            return [];
        });
    }
}