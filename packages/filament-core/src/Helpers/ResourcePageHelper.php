<?php

namespace RedJasmine\FilamentCore\Helpers;

use App\Models\User;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Support\Components\ViewComponent;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\ValidationException;
use RedJasmine\FilamentCore\Filters\TreeParent;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\Enums\CategoryStatusEnum;
use RedJasmine\Support\Exceptions\AbstractException;
use function Filament\Support\get_model_label;

/**
 * @property string $translationNamespace
 * @property string $service
 * @property string $createCommand
 * @property string $updateCommand
 * @property string $deleteCommand
 * @property string $dataClass
 */
trait ResourcePageHelper
{

    public static function getEloquentQuery() : Builder
    {


        $query = app(static::$service)->readRepository->modelQuery();

//        $query->withoutGlobalScopes([
//                                  SoftDeletingScope::class,
//                              ]);
        if (static::onlyOwner()) {
            $query->onlyOwner(auth()->user());
        }
        return $query;
    }


    public static function onlyOwner() : bool
    {
        return static::$onlyOwner ?? false;
    }

    public static function getDeleteCommand() : ?string
    {
        return static::$deleteCommand;
    }

    public static function callResolveRecord(Model $model) : Model
    {
        if ($model->relationLoaded('extension')) {
            foreach ($model->extension->getAttributes() as $key => $value) {
                $model->setAttribute($key, $model->extension->{$key});
            }
        }
        return $model;
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data) : array
    {

        $resource = static::getResource();
        if ($resource::onlyOwner()) {
            $data['owner_type'] = auth()->user()->getType();
            $data['owner_id']   = auth()->user()->getID();
        }


        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data) : array
    {
        $resource = static::getResource();
        if ($resource::onlyOwner()) {
            $data['owner_type'] = auth()->user()->getType();
            $data['owner_id']   = auth()->user()->getID();
        }


        return $data;
    }

    /**
     * @throws AbstractException
     */
    protected function handleRecordCreation(array $data) : Model
    {

//        dd($data);
        $resource = static::getResource();

        try {
            $commandService = app($resource::getService());
            $command        = ($resource::getCreateCommand())::from($data);

            return $commandService->create($command);
        } catch (ValidationException $exception) {

            Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();
            throw $exception;
        } catch (AbstractException $abstractException) {
            Notification::make()
                        ->title($abstractException->getMessage())
                        ->danger()
                        ->send();
            report($abstractException);
            throw ValidationException::withMessages([]);
        }
    }

    public static function getService() : ?string
    {
        return static::$service;
    }


    public static function getCreateCommand() : ?string
    {
        return static::$createCommand;
    }

    protected function resolveRecord(int|string $key) : Model
    {
        $resource     = static::getResource();
        $queryService = app($resource::getService());


        if ($resource::onlyOwner()) {
            $queryService->readRepository->withQuery(fn($query) => $query->onlyOwner(auth()->user()));
        }
        $model = $queryService->find($resource::callFindQuery(FindQuery::make($key)));

        return $resource::callResolveRecord($model);

    }

    public static function getQueryService() : ?string
    {
        return static::$service;
    }

    public static function callFindQuery(FindQuery $findQuery) : FindQuery
    {
        return $findQuery;
    }

    /**
     * @throws AbstractException
     */
    protected function handleRecordUpdate(Model $record, array $data) : Model
    {


        try {
            $resource       = static::getResource();
            $commandService = app($resource::getService());
            $data['id']     = $record->getKey();

            $command = ($resource::getUpdateCommand())::from($data);

            $command->setKey($record->getKey());
            return $commandService->update($command);
        } catch (ValidationException $exception) {

            Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();
            throw $exception;
        } catch (AbstractException $abstractException) {
            Notification::make()
                        ->title($abstractException->getMessage())
                        ->danger()
                        ->send();
            report($abstractException);
            throw ValidationException::withMessages([]);
        }
    }

    public static function getUpdateCommand() : ?string
    {
        return static::$updateCommand;
    }


    public static function makeUser()
    {

    }

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
        return [

            Forms\Components\MorphToSelect::make('creator')
                                          ->label(__('red-jasmine-support::support.creator'))
                                          ->types([
                                              // TODO 更具当前 model 动态
                                              Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('name')
                                          ])
                                          ->columns(2)
                                          ->visibleOn('view'),

            Forms\Components\MorphToSelect::make('updater')
                                          ->label(__('red-jasmine-support::support.updater'))
                                          ->types([
                                              // TODO 更具当前 model 动态
                                              Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('name')
                                          ])
                                          ->columns(2)
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
            Tables\Columns\TextColumn::make('creator_type')
                                     ->label(__('red-jasmine-support::support.creator_type'))
                                     ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('creator_id')
                                     ->label(__('red-jasmine-support::support.creator_id'))
                                     ->numeric()
                                     ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updater')
                                     ->formatStateUsing(fn($state) => $state?->getNickname())
                                     ->label(__('red-jasmine-support::support.updater'))
                                     ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updater_type')
                                     ->label(__('red-jasmine-support::support.updater_type'))
                                     ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updater_id')
                                     ->label(__('red-jasmine-support::support.updater_id'))
                                     ->numeric()
                                     ->toggleable(isToggledHiddenByDefault: true),
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
        return static fn(Builder $query, Forms\Get $get) => $query->onlyOwner(UserData::from([
            'type' => $get('owner_type'), 'id' => $get('owner_id')
        ]));
    }

    public static function ownerFormSchemas(string $name = 'owner') : array
    {

        return [

            // Forms\Components\MorphToSelect::make($name)
            //                               ->label(__('red-jasmine-support::support.owner'))
            //                               ->types([
            //                                           // TODO 更具当前 model 动态
            //                                           Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('nickname')
            //                                       ])
            //                               ->live()
            //                               ->columns(2)
            //                               ->default([ $name . '_type' => auth()->user()->getType(), $name . '_id' => auth()->user()->getID() ])
            //                               ->hidden(!auth()->user()->isAdmin())
            // ,

            Forms\Components\TextInput::make($name.'_type')
                                      ->label(__('red-jasmine-support::support.owner_type'))
                                      ->hidden(!auth()->user()->isAdmin())
                                      ->default(auth()->user()->getType())
                                      ->required()
                                      ->maxLength(64)
                                      ->live(),
            Forms\Components\TextInput::make($name.'_id')
                                      ->label(__('red-jasmine-support::support.owner_id'))
                                      ->required()
                                      ->hidden(!auth()->user()->isAdmin())
                                      ->default(auth()->user()->getID())
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


    public static function translationLabels(ViewComponent $component) : ViewComponent
    {
        if (property_exists($component, 'label') && !$component->isSetLabel()) {
            $component->label(static function (ViewComponent $component) {
                return __(static::$translationNamespace.'.fields.'.$component->getName());
            });
        }

        if (method_exists($component, 'getComponents')) {

            foreach ($component->getComponents(true) as $childComponent) {
                static::translationLabels($childComponent);
            }

        }
        if (method_exists($component, 'getChildComponents')) {

//            foreach ($component->getChildComponents(true) as $childComponent) {
////
//                try {
//
//                    static::translationLabels($childComponent);
//                }catch (\Throwable $throwable){
//                        dd($childComponent);
//                }
//
//            }

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


        if ($component instanceof Infolist) {

//            foreach ($component->getComponents(true) as $entity) {
//
//                static::translationLabels($entity);
//            }

        }


        return $component;
    }



    public static function categoryForm(Form $form) : Form
    {
        return $form
            ->columns(1)
            ->schema([

                SelectTree::make('parent_id')
                          ->label(__('red-jasmine-support::category.fields.parent_id'))
                          ->relationship(relationship: 'parent', titleAttribute: 'name', parentAttribute: 'parent_id',
                              modifyQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
                              modifyChildQueryUsing: fn($query, Forms\Get $get, ?Model $record) => $query->when($record?->getKey(),
                                  fn($query, $value) => $query->where('id', '<>', $value)),
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
                Forms\Components\TextInput::make('description')
                                          ->label(__('red-jasmine-support::category.fields.description'))->maxLength(255),
                Forms\Components\FileUpload::make('image')
                                           ->label(__('red-jasmine-support::category.fields.image'))
                                           ->image(),
                Forms\Components\TextInput::make('cluster')
                                          ->label(__('red-jasmine-support::category.fields.cluster'))
                                          ->maxLength(255),
                Forms\Components\TextInput::make('sort')
                                          ->label(__('red-jasmine-support::category.fields.sort'))
                                          ->required()
                                          ->default(0),
                Forms\Components\Radio::make('is_leaf')
                                      ->label(__('red-jasmine-support::category.fields.is_leaf'))
                                      ->required()
                                      ->boolean()
                                      ->inline()
                                      ->default(false),
                Forms\Components\Radio::make('is_show')
                                      ->label(__('red-jasmine-support::category.fields.is_show'))
                                      ->required()
                                      ->boolean()
                                      ->inline()
                                      ->default(true),
                Forms\Components\ToggleButtons::make('status')
                                              ->label(__('red-jasmine-support::category.fields.status'))
                                              ->required()
                                              ->inline()

                                              ->default(CategoryStatusEnum::ENABLE)
                                              ->useEnum(CategoryStatusEnum::class),
            ]);
    }

    public static function categoryTable(Table $table) : Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                                         ->label(__('red-jasmine-support::category.fields.id'))
                                         ->sortable()->copyable(),
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
                                         ->boolean(),

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
                                           ->options(CategoryStatusEnum::options()),
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
