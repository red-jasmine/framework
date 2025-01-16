<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Repositories\SettleReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class SettleReadRepository extends QueryBuilderReadRepository implements SettleReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Settle::class;

}
