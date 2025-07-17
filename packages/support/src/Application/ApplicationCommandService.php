<?php

namespace RedJasmine\Support\Application;


use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;


/**
 * @deprecated
 * @method Model create(Data $command)
 * @method void  update(Data $command)
 * @method void  delete(Data $command)
 * @property RepositoryInterface $repository
 */
abstract class ApplicationCommandService extends Service
{
    /**
     * @var string
     */
    protected static string $modelClass;


    protected static $macros = [
        'create' => CreateCommandHandler::class,
        'update' => UpdateCommandHandler::class,
        'delete' => DeleteCommandHandler::class,
    ];

    public function getRepository() : RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param  null  $command
     *
     * @return Model
     * @throws Exception
     * @deprecated
     */
    public function newModel($command = null) : Model
    {
        /**
         * @var $model Model
         */
        return static::$modelClass::make();
    }

    public static function getModelClass() : string
    {
        return static::$modelClass;
    }


}
