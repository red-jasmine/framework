<?php

namespace RedJasmine\Support\Application;


use Exception;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Application\CommandHandlers\CreateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\UpdateCommandHandler;
use RedJasmine\Support\Application\CommandHandlers\DeleteCommandHandler;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Support\Foundation\Service\Service;
use RedJasmine\Support\Helpers\ID\Snowflake;


/**
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

    public static function getModelClass() : string
    {
        return static::$modelClass;
    }

    public function getRepository() : RepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @param  null  $command
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


    protected function hooks() : array
    {
        return [
            'create' => [],
            'update' => [],
            'delete' => []
        ];
    }

}
