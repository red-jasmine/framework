<?php

namespace RedJasmine\Support\Application;


use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


/**
 * @method Model create(Data $command)
 * @method void  update(Data $command)
 * @method void  delete(Data $command)
 * @property RepositoryInterface $repository
 */
abstract class ApplicationCommandService extends ApplicationService
{
    protected static string $modelClass;

    protected function pipelines() : array
    {
        return [
            'create' => [],
            'update' => [],
            'delete' => [],
        ];
    }

    protected ?string $pipelinesConfigKeyPrefix = null;

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

    public function makeMacro(mixed $macro, $method, $parameters) : mixed
    {
        $macro = parent::makeMacro($macro, $method, $parameters);
        if ($macro instanceof CommandHandler) {
            if ($this->pipelinesConfigKeyPrefix) {
                // 设置配置的
                $macro->setPipelinesConfigKeyPrefix($this->pipelinesConfigKeyPrefix);
                $macro->addPipeline($this->pipelines()[$method] ?? []);
                // 设置
                $macro->initializePipelineTrait();
            }
        }
        return $macro;
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
