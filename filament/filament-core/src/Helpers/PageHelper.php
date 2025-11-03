<?php

namespace RedJasmine\FilamentCore\Helpers;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Field;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Support\Components\ViewComponent;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\FilamentCore\Filters\TreeParent;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

/**
 * @property static $translationNamespace
 */
trait PageHelper
{

    public static function operateFormSchemas() : array
    {
        return [
            TextInput::make('creator_type')
                     ->label(__('red-jasmine-support::support.creator_type'))
                     ->maxLength(64)
                     ->visibleOn('view'),
            TextInput::make('creator_id')
                     ->label(__('red-jasmine-support::support.creator_id'))
                     ->required()
                     ->visibleOn('view'),
            TextInput::make('updater_type')
                     ->label(__('red-jasmine-support::support.updater_type'))
                     ->maxLength(64)
                     ->visibleOn('view'),

            TextInput::make('updater_id')
                     ->label(__('red-jasmine-support::support.updater_id'))
                     ->visibleOn('view'),
        ];

    }

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

    public static function categoryForm(Schema $schema, bool $hasOwner = false) : Schema
    {
        $owner = $hasOwner ? static::ownerFormSchemas() : [];

        return $schema
            ->components([
                ...$owner,
                Flex::make([
                    Section::make([

                        SelectTree::make('parent_id')
                                  ->label(__('red-jasmine-support::category.fields.parent_id'))
                                  ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id',
                                      modifyQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                          ->when($hasOwner,
                                              fn($query, $value) => $query->where('owner_type', $get('owner_type'))
                                                                          ->where('owner_id', $get('owner_id')))
                                          ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                      modifyChildQueryUsing: fn($query, Get $get, ?Model $record) => $query
                                          ->when($hasOwner, fn($query, $value) => $query->where('owner_type', $get('owner_type'))
                                                                                        ->where('owner_id', $get('owner_id')))
                                          ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                  )
                                  ->searchable()
                                  ->default(0)
                                  ->enableBranchNode()
                                  ->parentNullValue(0)
                                  ->dehydrateStateUsing(fn($state) => (int) $state),

                        TextInput::make('name')
                                 ->label(__('red-jasmine-support::category.fields.name'))
                                 ->required()
                                 ->maxLength(255),

                        ToggleButtons::make('is_leaf')
                                     ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                     ->required()
                                     ->boolean()
                                     ->inline()
                                     ->inlineLabel()
                                     ->default(false),
                        TextInput::make('slug')
                                 ->label(__('red-jasmine-support::category.fields.slug'))
                                 ->maxLength(255),
                        TextInput::make('description')
                                 ->label(__('red-jasmine-support::category.fields.description'))->maxLength(255),
                        FileUpload::make('image')
                                  ->label(__('red-jasmine-support::category.fields.image'))
                                  ->image(),
                        FileUpload::make('icon')
                                  ->label(__('red-jasmine-support::category.fields.icon'))
                                  ->image(),
                        ColorPicker::make('color')
                                   ->label(__('red-jasmine-support::category.fields.color'))
                        ,
                        TextInput::make('cluster')
                                 ->label(__('red-jasmine-support::category.fields.cluster'))
                                 ->maxLength(255),

                        KeyValue::make('extra')
                                ->label(__('red-jasmine-user::user-group.fields.extra')),

                    ]),
                    Section::make([


                        TextInput::make('sort')
                                 ->label(__('red-jasmine-support::category.fields.sort'))
                                 ->required()
                                 ->default(0),

                        Toggle::make('is_show')
                              ->label(__('red-jasmine-support::category.fields.is_show'))
                              ->required()
                              ->inline()
                              ->default(true),
                        ToggleButtons::make('status')
                                     ->label(__('red-jasmine-support::category.fields.status'))
                                     ->required()
                                     ->inline()
                                     ->default(UniversalStatusEnum::ENABLE)
                                     ->useEnum(UniversalStatusEnum::class),

                    ])->grow(false),


                ])->columnSpanFull(),

            ]);
    }

    // 做成表单组件
    public static function ownerFormSchemas(string $name = 'owner') : array
    {
        $user     = auth()->user();
        $owner    = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
        $disabled = true;
        if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            $disabled = false;
        }

        // use Filament\Schemas\Components\FusedGroup;
        return [
            FusedGroup::make([
                TextInput::make($name.'_type')
                         ->prefix(__('red-jasmine-support::support.owner_type'))
                         ->label(__('red-jasmine-support::support.owner_type'))
                         ->default($owner->getType())
                         ->required()
                         ->maxLength(64)
                         ->disabled($disabled)
                         ->live(),
                TextInput::make($name.'_id')
                    ->prefix(__('red-jasmine-support::support.owner_id'))
                         ->label(__('red-jasmine-support::support.owner_id'))
                         ->required()
                         ->default($owner->getID())
                         ->disabled($disabled)
                         ->live(),
            ])->columns(2)
                      ->label(__('red-jasmine-support::support.owner')),


        ];


    }

    public static function categoryTable(Table $table, bool $hasOwner = false) : Table
    {
        $owner = $hasOwner ? static::ownerTableColumns() : [];
        return $table
            ->columns([
                ...$owner,
                TextColumn::make('id')
                          ->label(__('red-jasmine-support::category.fields.id'))
                          ->copyable(),
                TextColumn::make('parent.name')
                          ->label(__('red-jasmine-support::category.fields.parent_id'))
                          ->sortable(),
                TextColumn::make('name')
                          ->label(__('red-jasmine-support::category.fields.name'))
                          ->searchable()->copyable(),
                ImageColumn::make('image')
                           ->label(__('red-jasmine-support::category.fields.image'))
                ,
                TextColumn::make('cluster')
                          ->label(__('red-jasmine-support::category.fields.cluster'))
                          ->searchable(),
                IconColumn::make('is_leaf')
                          ->label(__('red-jasmine-support::category.fields.is_leaf'))
                          ->boolean()
                          ->toggleable(isToggledHiddenByDefault: true)
                ,

                TextColumn::make('sort')
                          ->label(__('red-jasmine-support::category.fields.sort'))
                          ->sortable(),
                TextColumn::make('status')
                          ->label(__('red-jasmine-support::category.fields.status'))
                          ->useEnum(),
                IconColumn::make('is_show')
                          ->label(__('red-jasmine-support::category.fields.is_show'))
                          ->boolean(),

                ... static::operateTableColumns(),
            ])
            ->filters([
                TreeParent::make('tree')->label(__('red-jasmine-support::category.fields.parent_id')),
                SelectFilter::make('status')
                            ->label(__('red-jasmine-support::category.fields.status'))
                            ->options(UniversalStatusEnum::options()),
                TernaryFilter::make('is_show')
                             ->label(__('red-jasmine-support::category.fields.is_show'))
                ,

            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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

    public static function operateTableColumns() : array
    {
        return [
            TextColumn::make('creator')
                      ->formatStateUsing(fn($state) => $state?->getNickname())
                      ->label(__('red-jasmine-support::support.creator'))
                      ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_type')
            //                          ->label(__('red-jasmine-support::support.creator_type'))
            //                          ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_id')
            //                          ->label(__('red-jasmine-support::support.creator_id'))
            //                          ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updater')
                      ->formatStateUsing(fn($state) => $state?->getNickname() ?? $state?->getId())
                      ->label(__('red-jasmine-support::support.updater'))
                      ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('updater_type')
            //                          ->label(__('red-jasmine-support::support.updater_type'))
            //                          ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('updater_id')
            //                          ->label(__('red-jasmine-support::support.updater_id'))
            //
            //                          ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                      ->label(__('red-jasmine-support::support.created_at'))
                      ->dateTime()
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                      ->label(__('red-jasmine-support::support.updated_at'))
                      ->dateTime()
                      ->sortable()
                      ->toggleable(isToggledHiddenByDefault: true),

        ];
    }
}
