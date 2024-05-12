<?php

namespace RedJasmine\Support\Application;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;
use RedJasmine\Support\Infrastructure\Repositories\RepositoryInterface;


/**
 * @property RepositoryInterface $repository
 */
abstract class ApplicationCommandService extends ApplicationService
{
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

    public function newModel() : Model
    {
        return new  static::$modelClass;
    }


    /**
     * 自定义调用
     *
     * @param $macro
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function callMacro($macro, $method, $parameters) : mixed
    {
        if ($macro instanceof CommandHandler) {
            return $macro->setArguments($parameters)->handle(...$parameters);
        }

        return $macro(...$parameters);

    }


}
