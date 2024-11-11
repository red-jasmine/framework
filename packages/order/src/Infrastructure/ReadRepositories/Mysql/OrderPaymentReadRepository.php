<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderPaymentReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


class OrderPaymentReadRepository extends QueryBuilderReadRepository implements OrderPaymentReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = OrderPayment::class;


}
