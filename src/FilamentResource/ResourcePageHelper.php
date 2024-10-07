<?php

namespace RedJasmine\FilamentCore\FilamentResource;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Exceptions\AbstractException;

/**
 * 提炼到公共组建中
 */
trait ResourcePageHelper
{

    public static function getEloquentQuery() : Builder
    {

        return app(static::$queryService)->getRepository()->modelQuery();
    }

    public static function getDeleteCommand() : ?string
    {
        return static::$deleteCommand;
    }

    public static function getUpdateCommand() : ?string
    {
        return static::$updateCommand;
    }

    public static function getCreateCommand() : ?string
    {
        return static::$createCommand;
    }

    public static function getQueryService() : ?string
    {
        return static::$queryService;
    }

    public static function getCommandService() : ?string
    {
        return static::$commandService;
    }


    /**
     * @throws AbstractException
     */
    protected function handleRecordCreation(array $data) : Model
    {
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

    protected function resolveRecord(int|string $key) : Model
    {
        $resource     = static::getResource();
        $queryService = app($resource::getQueryService());
        return $queryService->findById(FindQuery::make($key));

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
}
