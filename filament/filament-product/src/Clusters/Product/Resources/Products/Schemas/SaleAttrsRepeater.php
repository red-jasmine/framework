<?php

namespace RedJasmine\FilamentProduct\Clusters\Product\Resources\Products\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use RedJasmine\Product\Domain\Attribute\Models\Enums\ProductAttributeTypeEnum;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttribute;
use RedJasmine\Product\Domain\Attribute\Models\ProductAttributeValue;


class SaleAttrsRepeater extends Repeater
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

        $this->label(__('red-jasmine-product::product.fields.sale_attrs'))
             ->schema([
                 Hidden::make('aid')
                       ->label(__('red-jasmine-product::product.attrs.aid'))
                       ->required()
                       ->dehydrated(),

                 TextInput::make('name')
                          ->label(__('red-jasmine-product::product.attrs.aid'))
                          ->readOnly()
                          ->required()
                          ->columnSpan(1),

                 Repeater::make('values')
                         ->table([
                             Repeater\TableColumn::make(__('red-jasmine-product::product-attribute-value.labels.product-attribute-value')),
                             Repeater\TableColumn::make('别名'),
                         ])
                         ->label(__('red-jasmine-product::product.attrs.values'))
                         ->schema([
                             Hidden::make('vid')
                                   ->label(__('red-jasmine-product::product.attrs.alias')),

                             TextInput::make('name')
                                      ->label(__('red-jasmine-product::product.attrs.alias'))
                             ,

                             TextInput::make('alias')
                                      ->label(__('red-jasmine-product::product.attrs.alias'))
                                      ->hiddenLabel()
                                      ->live(onBlur: true)
                                      ->placeholder('请输入别名')
                                      ->maxLength(30)
                         ])
                         ->hiddenLabel()
                         ->addAction(function (Action $action, Get $get, Set $set, $state) {
                             $action->icon(Heroicon::Envelope)
                                    ->schema([
                                        CheckboxList::make('vid')
                                                    ->columns(6)
                                                    ->label(__('red-jasmine-product::product.attrs.vid'))
                                                    ->required()
                                                    ->hiddenLabel()
                                                    ->options(fn() => ProductAttributeValue::where('aid', $get('aid'))
                                                                                           ->pluck('name', 'id')
                                                                                           ->toArray()),
                                    ])
                                    ->action(function (array $data, array $arguments, Repeater $component) use (
                                        $set,
                                        $get,
                                        $state
                                    ) : void {
                                        $vidList = $data['vid'] ?? [];
                                        $vidList = ProductAttributeValue::select(['name', 'id'])->find($vidList);
                                        $items   = [];
                                        foreach ($vidList as $attributeValue) {
                                            $items[] = [
                                                'vid'   => (string) $attributeValue->id,
                                                'alias' => '',
                                                'name'  => $attributeValue->name,
                                            ];
                                        }
                                        $values = $get('values') ?? [];
                                        if (!is_array($values)) {
                                            $values = [];
                                        }
                                        array_push($values, ...$items);
                                        $values = array_values(array_filter($values, function ($item) {
                                            // 过滤掉 Filament 的数组标记 {"s": "arr"}
                                            if (is_array($item) && isset($item['s']) && $item['s'] === 'arr') {
                                                return false;
                                            }

                                            return is_array($item) && isset($item['vid']) && !empty($item['vid']);
                                        }));

                                        $set('values', $values, shouldCallUpdatedHooks: true);
                                    });
                         })
                         ->minItems(1)
                         ->deletable()
                         ->default([])
                         ->inlineLabel(false)
                         ->columnSpanFull()
                         ->reorderable(false)
                 ,
             ])
            ->addAction(function (Action $action, Get $get, Set $set) {
                $action->icon(Heroicon::Plus)
                       ->label('快速添加销售属性')
                       ->schema([
                           Select::make('aid')
                                 ->label(__('red-jasmine-product::product.attrs.aid'))
                                 ->live()
                                 ->required()
                                 ->options(ProductAttribute::limit(10)->pluck('name', 'id')->toArray())
                                 ->searchable()
                                 ->getSearchResultsUsing(fn(string $search) : array => ProductAttribute::where('name', 'like',
                                     "%{$search}%")),

                           CheckboxList::make('vids')
                                       ->label(__('red-jasmine-product::product.attrs.values'))
                                       ->columns(6)
                                       ->required()
                                       ->options(fn(Get $get) => $get('aid')
                                           ? ProductAttributeValue::where('aid', $get('aid'))->pluck('name', 'id')->toArray()
                                           : []
                                       )
                                       ->hidden(fn(Get $get) => !$get('aid')),
                       ])
                       ->action(function (array $data) use ($get, $set) : void {
                           $aid  = $data['aid'] ?? null;
                           $vids = $data['vids'] ?? [];

                           if ($aid && !empty($vids)) {
                               $attribute = ProductAttribute::find($aid);
                               if ($attribute) {
                                   $attributeValues = ProductAttributeValue::select(['id', 'name'])
                                                                           ->whereIn('id', $vids)
                                                                           ->get();

                                   $values = [];
                                   foreach ($attributeValues as $attrValue) {
                                       $values[] = [
                                           'vid'   => (string) $attrValue->id,
                                           'name'  => $attrValue->name,
                                           'alias' => '',
                                       ];
                                   }

                                   $saleAttrs = $get('sale_attrs') ?? [];
                                   if (!is_array($saleAttrs)) {
                                       $saleAttrs = [];
                                   }
                                   $saleAttrs[] = [
                                       'aid'    => (string) $attribute->id,
                                       'name'   => $attribute->name,
                                       'values' => $values,
                                   ];
                                   $saleAttrs   = array_values(array_filter($saleAttrs, function ($item) {
                                       return is_array($item) && isset($item['aid']) && !empty($item['aid']);
                                   }));
                                   $set('sale_attrs', $saleAttrs, shouldCallUpdatedHooks: true);
                               }
                           }
                       });
            })
            ->deletable(true)
            ->default([])
            ->addActionAlignment(Alignment::Start)
            ->inlineLabel(false)
            ->columnSpan('full')
            ->reorderable(false);
    }
}
