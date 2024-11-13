<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Order\Domain\Repositories\OrderCardKeyReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


class OrderCardKeyReadRepository extends QueryBuilderReadRepository implements OrderCardKeyReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = OrderCardKey::class;


}
