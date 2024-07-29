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
    public function __construct()
    {
    }


    protected static string $modelClass;


    /**
     * 扩展管道配置
     * @return array
     */
    protected function pipelines() : array
    {
        return [
            'create' => [],
            'update' => [],
            'delete' => [],
        ];
    }

    /**
     * 管道配置前缀
     * @var string|null
     */
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
     * @param null $command
     *
     * @return Model
     * @throws Exception
     */
    public function newModel($command = null) : Model
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


    /**
     * 自定义调用
     *
     * @param $macro
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function callMacros($macro, $method, $parameters) : mixed
    {

        if ($macro instanceof CommandHandler) {

            return $macro->handle(...$parameters);
        }

        return $macro(...$parameters);

    }


}
