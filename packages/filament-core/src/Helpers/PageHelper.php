<?php

namespace RedJasmine\FilamentCore\Helpers;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Support\Components\ViewComponent;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
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
            Forms\Components\TextInput::make('creator_type')
                                      ->label(__('red-jasmine-support::support.creator_type'))
                                      ->maxLength(64)
                                      ->visibleOn('view'),
            Forms\Components\TextInput::make('creator_id')
                                      ->label(__('red-jasmine-support::support.creator_id'))
                                      ->required()
                                      ->visibleOn('view'),
            Forms\Components\TextInput::make('updater_type')
                                      ->label(__('red-jasmine-support::support.updater_type'))
                                      ->maxLength(64)
                                      ->visibleOn('view'),

            Forms\Components\TextInput::make('updater_id')
                                      ->label(__('red-jasmine-support::support.updater_id'))
                                      ->visibleOn('view'),
        ];

    }

    public static function operateTableColumns() : array
    {
        return [
            Tables\Columns\TextColumn::make('creator')
                                     ->formatStateUsing(fn($state) => $state?->getNickname())
                                     ->label(__('red-jasmine-support::support.creator'))
                                     ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_type')
            //                          ->label(__('red-jasmine-support::support.creator_type'))
            //                          ->toggleable(isToggledHiddenByDefault: true),
            // Tables\Columns\TextColumn::make('creator_id')
            //                          ->label(__('red-jasmine-support::support.creator_id'))
            //                          ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updater')
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
            Tables\Columns\TextColumn::make('created_at')
                                     ->label(__('red-jasmine-support::support.created_at'))
                                     ->dateTime()
                                     ->sortable()
                                     ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                                     ->label(__('red-jasmine-support::support.updated_at'))
                                     ->dateTime()
                                     ->sortable()
                                     ->toggleable(isToggledHiddenByDefault: true),

        ];
    }

    public static function ownerQueryUsing(string $name = 'owner') : callable
    {
        return static fn($query, Forms\Get $get) => $query->onlyOwner(UserData::from([
            'type' => $get('owner_type'), 'id' => $get('owner_id')
        ]));
    }

    public static function ownerFormSchemas(string $name = 'owner') : array
    {
        $user     = auth()->user();
        $owner    = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
        $disabled = true;
        if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            $disabled = false;
        }
        return [
            Forms\Components\TextInput::make($name.'_type')
                                      ->label(__('red-jasmine-support::support.owner_type'))
                                      ->default($owner->getType())
                                      ->required()
                                      ->maxLength(64)
                                      ->disabled($disabled)
                                      ->live(),
            Forms\Components\TextInput::make($name.'_id')
                                      ->label(__('red-jasmine-support::support.owner_id'))
                                      ->required()
                                      ->default($owner->getID())
                                      ->disabled($disabled)
                                      ->live(),

        ];


    }


    public static function ownerTableColumns(string $name = 'owner') : array
    {
        // 定义 组件
        return [
            Tables\Columns\TextColumn::make($name)
                                     ->formatStateUsing(fn($state) => $state?->getNickname())
                                     ->label(__('red-jasmine-support::support.owner'))
                                     ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make($name.'_type')->label(__('red-jasmine-support::support.owner_type'))->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make($name.'_id')->label(__('red-jasmine-support::support.owner_id'))->numeric()->copyable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }


    public static function translationLabels(ViewComponent $component, array $parent = []) : ViewComponent
    {
        // 设置自身的字段
        if (property_exists($component, 'label') && !$component->isSetLabel()) {
            $component->label(static function (ViewComponent $component) use ($parent) {
                /**
                 * @var Forms\Components\Field $component
                 */
                $name = $component->getName();

                if (method_exists($component, 'childComponents') && count($component->getChildComponents()) > 0) {
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
                    $column->label(static function (Tables\Columns\Column $column) {
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
                    $filter->label(static function (Tables\Filters\BaseFilter $filter) {
                        return __(static::$translationNamespace.'.fields.'.$filter->getName());
                    });
                }

            }


        }
        return $component;

    }


    public static function categoryForm(Form $form, bool $hasOwner = false) : Form
    {
        $owner = $hasOwner ? static::ownerFormSchemas() : [];

        return $form
            ->schema([
                ...$owner,
                Forms\Components\Split::make([
                    Forms\Components\Section::make([

                        SelectTree::make('parent_id')
                                  ->label(__('red-jasmine-support::category.fields.parent_id'))
                                  ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id',
                                      modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query
                                          ->when($hasOwner,
                                              fn($query, $value) => $query->where('owner_type', $get('owner_type'))
                                                                          ->where('owner_id', $get('owner_id')))
                                          ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                      modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query
                                          ->when($hasOwner, fn($query, $value) => $query->where('owner_type', $get('owner_type'))
                                                                                        ->where('owner_id', $get('owner_id')))
                                          ->when($record?->getKey(), fn($query, $value) => $query->where('id', '<>', $value)),
                                  )
                                  ->searchable()
                                  ->default(0)
                                  ->enableBranchNode()
                                  ->parentNullValue(0)
                                  ->dehydrateStateUsing(fn($state) => (int) $state),

                        Forms\Components\TextInput::make('name')
                                                  ->label(__('red-jasmine-support::category.fields.name'))
                                                  ->required()
                                                  ->maxLength(255),

                        Forms\Components\ToggleButtons::make('is_leaf')
                                                      ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                                      ->required()
                                                      ->boolean()
                                                      ->inline()
                                                      ->inlineLabel()
                                                      ->default(false),
                        Forms\Components\TextInput::make('slug')
                                                  ->label(__('red-jasmine-support::category.fields.slug'))
                                                  ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                                                  ->label(__('red-jasmine-support::category.fields.description'))->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                                                   ->label(__('red-jasmine-support::category.fields.image'))
                                                   ->image(),
                        Forms\Components\FileUpload::make('icon')
                                                   ->label(__('red-jasmine-support::category.fields.icon'))
                                                   ->image(),
                        Forms\Components\ColorPicker::make('color')
                                                    ->label(__('red-jasmine-support::category.fields.color'))
                        ,
                        Forms\Components\TextInput::make('cluster')
                                                  ->label(__('red-jasmine-support::category.fields.cluster'))
                                                  ->maxLength(255),

                        Forms\Components\KeyValue::make('extra')
                                                 ->label(__('red-jasmine-user::user-group.fields.extra')),

                    ]),
                    Forms\Components\Section::make([


                        Forms\Components\TextInput::make('sort')
                                                  ->label(__('red-jasmine-support::category.fields.sort'))
                                                  ->required()
                                                  ->default(0),

                        Forms\Components\Toggle::make('is_show')
                                               ->label(__('red-jasmine-support::category.fields.is_show'))
                                               ->required()
                                               ->inline()
                                               ->default(true),
                        Forms\Components\ToggleButtons::make('status')
                                                      ->label(__('red-jasmine-support::category.fields.status'))
                                                      ->required()
                                                      ->inline()
                                                      ->default(UniversalStatusEnum::ENABLE)
                                                      ->useEnum(UniversalStatusEnum::class),

                    ])->grow(false),


                ])->columnSpanFull(),

            ]);
    }

    public static function categoryTable(Table $table, bool $hasOwner = false) : Table
    {
        $owner = $hasOwner ? static::ownerTableColumns() : [];
        return $table
            ->columns([
                ...$owner,
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-support::category.fields.id'))
                                         ->copyable(),
                Tables\Columns\TextColumn::make('parent.name')
                                         ->label(__('red-jasmine-support::category.fields.parent_id'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('name')
                                         ->label(__('red-jasmine-support::category.fields.name'))
                                         ->searchable()->copyable(),
                Tables\Columns\ImageColumn::make('image')
                                          ->label(__('red-jasmine-support::category.fields.image'))
                ,
                Tables\Columns\TextColumn::make('cluster')
                                         ->label(__('red-jasmine-support::category.fields.cluster'))
                                         ->searchable(),
                Tables\Columns\IconColumn::make('is_leaf')
                                         ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                         ->boolean()
                                         ->toggleable(isToggledHiddenByDefault: true)
                ,

                Tables\Columns\TextColumn::make('sort')
                                         ->label(__('red-jasmine-support::category.fields.sort'))
                                         ->sortable(),
                Tables\Columns\TextColumn::make('status')
                                         ->label(__('red-jasmine-support::category.fields.status'))
                                         ->useEnum(),
                Tables\Columns\IconColumn::make('is_show')
                                         ->label(__('red-jasmine-support::category.fields.is_show'))
                                         ->boolean(),

                ... static::operateTableColumns(),
            ])
            ->filters([
                TreeParent::make('tree')->label(__('red-jasmine-support::category.fields.parent_id')),
                Tables\Filters\SelectFilter::make('status')
                                           ->label(__('red-jasmine-support::category.fields.status'))
                                           ->options(UniversalStatusEnum::options()),
                Tables\Filters\TernaryFilter::make('is_show')
                                            ->label(__('red-jasmine-support::category.fields.is_show'))
                ,

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
