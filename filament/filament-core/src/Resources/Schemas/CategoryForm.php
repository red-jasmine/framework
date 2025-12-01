<?php

namespace RedJasmine\FilamentCore\Resources\Schemas;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Guava\IconPicker\Forms\Components\IconPicker;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Forms\Components\TranslatableTabs;
use RedJasmine\FilamentCore\Forms\Components\TransTabs;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

class CategoryForm
{


    public static function configure(Schema $schema, bool $hasOwner = false) : Schema
    {
        $owner = $hasOwner ? [Owner::make()] : [];
        $schema->components([
            Flex::make([
                Section::make([
                    ...$owner,
                    SelectTree::make('parent_id')
                              ->label(__('red-jasmine-support::category.fields.parent_id'))
                              ->relationship(relationship: 'parent', titleAttribute: 'name',
                                  parentAttribute: 'parent_id',
                                  modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                      ->when($hasOwner, fn($query, $value) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                    ->where('owner_id',
                                                                                        $get('owner_id')))
                                      ->when($record?->getKey(),
                                          fn($query, $value) => $query->where('id', '<>', $value)),
                                  modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                      ->when($hasOwner, fn($query, $value) => $query->where('owner_type',
                                          $get('owner_type'))
                                                                                    ->where('owner_id',
                                                                                        $get('owner_id')))
                                      ->when($record?->getKey(),
                                          fn($query, $value) => $query->where('id', '<>', $value)),
                              )
                              ->searchable()
                              ->default(0)
                              ->enableBranchNode()
                              ->parentNullValue(0)
                              ->dehydrateStateUsing(fn($state) => (int) $state),

                    TransTabs::make('内容')
                                    ->schema(
                                        [
                                            TextInput::make('name')
                                                     ->label(__('red-jasmine-support::category.fields.name'))
                                                     ->required()
                                                     ->maxLength(255),
                                            TextInput::make('description')
                                                     ->label(__('red-jasmine-support::category.fields.description'))->maxLength(255),

                                            TextInput::make('cluster')
                                                     ->label(__('red-jasmine-support::category.fields.cluster'))
                                                     ->maxLength(255),

                                        ]
                                    )
                                    // ->localeOptions([
                                    //     'zh-CN' => '简体中文',
                                    //     'en-US' => 'English (US)',
                                    //     'ja-JP' => '日本語',
                                    // ])
                                    // ->defaultLocaleLabel('默认'),

        ,

                    KeyValue::make('extra')
                            ->default([])
                            ->label(__('red-jasmine-user::user-group.fields.extra')),

                ]),
                Section::make([
                    FileUpload::make('image')
                              ->label(__('red-jasmine-support::category.fields.image'))
                              ->image(),
                    IconPicker::make('icon')
                              ->label(__('red-jasmine-support::category.fields.icon'))
                    ,
                    ColorPicker::make('color')
                               ->label(__('red-jasmine-support::category.fields.color'))
                    ,
                    TextInput::make('slug')
                             ->label(__('red-jasmine-support::category.fields.slug'))
                             ->maxLength(255),
                    TextInput::make('sort')
                             ->label(__('red-jasmine-support::category.fields.sort'))
                             ->required()
                             ->default(0),

                    Toggle::make('is_show')
                          ->label(__('red-jasmine-support::category.fields.is_show'))
                          ->required()
                          ->inline()
                          ->default(true),
                    ToggleButtons::make('is_leaf')
                                 ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                 ->required()
                                 ->boolean()
                                 ->inline()
                                 ->inlineLabel()
                                 ->default(false),
                    ToggleButtons::make('status')
                                 ->label(__('red-jasmine-support::category.fields.status'))
                                 ->required()
                                 ->inline()
                                 ->default(UniversalStatusEnum::ENABLE)
                                 ->useEnum(UniversalStatusEnum::class),

                ])->grow(false),
            ])->columnSpanFull(),


        ]);


        return $schema;
    }
}
