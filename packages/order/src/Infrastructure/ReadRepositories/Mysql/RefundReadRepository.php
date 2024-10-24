<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Order\Domain\Models\OrderRefund;
use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class RefundReadRepository extends QueryBuilderReadRepository implements RefundReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = OrderRefund::class;


}
