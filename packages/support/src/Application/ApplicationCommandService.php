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
 * @method Model create(Data $command)
 * @method void  update(Data $command)
 * @method void  delete(Data $command)
 * @property RepositoryInterface $repository
 */
abstract class ApplicationCommandService extends ApplicationService
{
    /**
     * @var string
     */
    protected static string $modelClass;




    /**
     * 转换器
     * @var string|null
     */
    protected static ?string $transformerClass = null;

    public static function getTransformerClass() : ?string
    {
        return static::$transformerClass;
    }

    public static function setTransformerClass(?string $transformerClass) : void
    {
        static::$transformerClass = $transformerClass;
    }


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
     * @deprecated
     * @param  null  $command
     *
     * @return Model
     * @throws Exception
     */
    public function newModel($command = null) : Model
    {
        if (method_exists(static::getModelClass(), 'newModel')) {
            return static::getModelClass()::newModel();
        }
        /**
         * @var $model Model
         */
        return (static::getModelClass())::make();

    }

    public static function getModelClass() : string
    {
        return static::$modelClass;
    }


}
