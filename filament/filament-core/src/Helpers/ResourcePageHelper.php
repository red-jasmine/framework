<?php

namespace RedJasmine\FilamentCore\Helpers;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RedJasmine\Support\Contracts\BelongsToOwnerInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;

/**
 * @property string $translationNamespace
 * @property string $service
 * @property string $createCommand
 * @property string $updateCommand
 * @property string $deleteCommand
 * @property string $findQuery
 * @property string $dataClass
 */
trait ResourcePageHelper
{

    use PageHelper;

    public static function getEloquentQuery() : Builder
    {


        $query = app(static::$service)->repository->query();


        if (static::onlyOwner()) {
            $user = auth()->user();
            if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            } else {
                $owner = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
                $query->onlyOwner($owner);
            }
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

            $user = auth()->user();
            if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            } else {
                $owner              = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
                $data['owner_type'] = $owner->getType();
                $data['owner_id']   = $owner->getID();
                //$query->onlyOwner($owner);
            }

        }


        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data) : array
    {
        // TODO 如果是总后台那么允许自定义
        $resource = static::getResource();
        if ($resource::onlyOwner()) {
            $user = auth()->user();
            if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            } else {
                $owner              = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
                $data['owner_type'] = $owner->getType();
                $data['owner_id']   = $owner->getID();
                //$query->onlyOwner($owner);
            }
        }


        return $data;
    }

    /**
     * @throws AbstractException
     */
    protected function handleRecordCreation(array $data) : Model
    {


        $resource = static::getResource();

        try {
            $commandService = app($resource::getService());



            $command = ($resource::getCreateCommand())::from($data);

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
        return static::$createCommand ?? static::$dataClass;
    }

    protected function resolveRecord(int|string $key) : Model
    {
        $resource     = static::getResource();
        $queryService = app($resource::getService());



        if (static::onlyOwner()) {
            $user = auth()->user();
            $owner = $user instanceof BelongsToOwnerInterface ? $user->owner() : $user;
            if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
            } else {
                $queryService->repository->withQuery(fn($query) => $query->onlyOwner($owner));
            }
        }
        $findQuery = static::getFindQuery()::make([]);
        $findQuery->setKey($key);

        $model = $queryService->find($resource::callFindQuery($findQuery));

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
            $command        = ($resource::getUpdateCommand())::from($data);
            $command->setKey($record->getKey());
            return  $commandService->update($command);

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
        return static::$updateCommand ?? static::$dataClass;
    }

    public static function getFindQuery():string
    {
        return  static::$resource::$findQuery ?? FindQuery::class;
    }

}
