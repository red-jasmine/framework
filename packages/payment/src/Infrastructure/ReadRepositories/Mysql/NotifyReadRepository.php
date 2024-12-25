<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\Notify;
use RedJasmine\Payment\Domain\Repositories\NotifyReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class NotifyReadRepository extends QueryBuilderReadRepository implements NotifyReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Notify::class;

}
