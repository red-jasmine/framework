<?php

namespace RedJasmine\FilamentCore\Helpers;

use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\ValidationException;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;

/**
 *
 */
trait ResourcePageHelper
{

    public static function getEloquentQuery() : Builder
    {

        $query = app(static::$queryService)->getRepository()->modelQuery();

        $query->withoutGlobalScopes([
                                  SoftDeletingScope::class,
                              ]);
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
     * @param array<string, mixed> $data
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
            $commandService = app($resource::getCommandService());
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

    public static function getCommandService() : ?string
    {
        return static::$commandService;
    }


    public static function getCreateCommand() : ?string
    {
        return static::$createCommand;
    }

    protected function resolveRecord(int|string $key) : Model
    {
        $resource     = static::getResource();
        $queryService = app($resource::getQueryService());

        if ($resource::onlyOwner()) {
            $queryService->getRepository()->withQuery(fn($query) => $query->onlyOwner(auth()->user()));
        }
        $model = $queryService->findById($resource::callFindQuery(FindQuery::make($key)));
        return $resource::callResolveRecord($model);

    }

    public static function getQueryService() : ?string
    {
        return static::$queryService;
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
            $commandService = app($resource::getCommandService());
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


    public static function operateFormSchemas() : array
    {
        return [

            Forms\Components\MorphToSelect::make('creator')
                                          ->label(__('red-jasmine-support::support.creator'))
                                          ->types([
                                                      // TODO 更具当前 model 动态
                                                      Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('name')
                                                  ])
                                          ->columns(2)
                                          ->default([ 'creator_type' => auth()->user()->getType(), 'creator_id' => auth()->user()->getID() ])
                                          ->visibleOn('view'),

            Forms\Components\MorphToSelect::make('updater')
                                          ->label(__('red-jasmine-support::support.updater'))
                                          ->types([
                                                      // TODO 更具当前 model 动态
                                                      Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('name')
                                                  ])
                                          ->columns(2)
                                          ->default([ 'updater_type' => auth()->user()->getType(), 'updater_id' => auth()->user()->getID() ])
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
        return static fn(Builder $query, Forms\Get $get) => $query->onlyOwner(UserData::from([ 'type' => $get('owner_type'), 'id' => $get('owner_id') ]));
    }
    public static function ownerFormSchemas(string $name = 'owner') : array
    {
        return [

            Forms\Components\MorphToSelect::make($name)
                                          ->label(__('red-jasmine-support::support.owner'))
                                          ->types([
                                                      // TODO 更具当前 model 动态
                                                      Forms\Components\MorphToSelect\Type::make(User::class)->titleAttribute('name')
                                                  ])
                                          ->live()
                                          ->columns(2)
                                          ->default([ $name . '_type' => auth()->user()->getType(), $name . '_id' => auth()->user()->getID() ])
                                          ->hidden(!auth()->user()->isAdmin())
            ,

            //            Forms\Components\TextInput::make($name . '_type')
            //                                      ->label(__('red-jasmine-support::support.owner_type'))
            //                                      ->hidden(!auth()->user()->isAdmin())
            //                                      ->default(auth()->user()->getType())
            //                                      ->required()
            //                                      ->maxLength(64)
            //                                      ->live(),
            //            Forms\Components\TextInput::make($name . '_id')
            //                                      ->label(__('red-jasmine-support::support.owner_id'))
            //                                      ->required()
            //                                      ->numeric()
            //                                      ->hidden(!auth()->user()->isAdmin())
            //                                      ->default(auth()->user()->getID())
            //                                      ->live(),

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

            Tables\Columns\TextColumn::make($name . '_type')->label(__('red-jasmine-support::support.owner_type'))   ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make($name . '_id')->label(__('red-jasmine-support::support.owner_id'))->numeric()->copyable()   ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
