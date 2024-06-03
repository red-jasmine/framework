<?php

namespace RedJasmine\Support\Application;


use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\Handlers\CreateCommandHandler;
use RedJasmine\Support\Application\Handlers\DeleteCommandHandler;
use RedJasmine\Support\Application\Handlers\UpdateCommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Helpers\ID\Snowflake;


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

    /**
     * @param null $data
     *
     * @return Model
     * @throws Exception
     */
    public function newModel($data = null) : Model
    {
        /**
         * @var $model Model
         */
        $model = new  static::$modelClass;
        if ($model->incrementing === false) {
            $model->{$model->getKeyName()} = $this->buildId();
        }
        return $model;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function buildId() : int
    {
        return Snowflake::getInstance()->nextId();
    }

    public function makeMacro(mixed $macro, $method, $parameters) : mixed
    {
        $macro = parent::makeMacro($macro, $method, $parameters);
        if ($macro instanceof CommandHandler) {
            $macro->addPipeline($this->pipelines()[$method] ?? []);
            if ($this->pipelinesConfigKeyPrefix) {
                // 设置配置的
                $macro->setPipelinesConfigKeyPrefix($this->pipelinesConfigKeyPrefix);
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
        // 在调用层 可以 设置 管道 TODO
        if ($macro instanceof CommandHandler) {
            return $macro->setArguments($parameters)->handle(...$parameters);
        }

        return $macro(...$parameters);

    }


}
