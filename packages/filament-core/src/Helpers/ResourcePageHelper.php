<?php

namespace RedJasmine\FilamentCore\Helpers;

use App\Models\User;
use Filament\Forms;
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
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;
use function Filament\Support\get_model_label;

/**
 * @property string $translationNamespace
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

            return $commandService->create(($resource::getCreateCommand())::from($data));
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
            return $commandService->update(($resource::getUpdateCommand())::from($data));
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


}
