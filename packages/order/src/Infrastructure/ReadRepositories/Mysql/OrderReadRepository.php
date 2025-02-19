<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


class OrderReadRepository extends QueryBuilderReadRepository implements OrderReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Order::class;

    public function findByNo(string $no) : Order
    {
        return $this->query()->where('order_no', $no)->firstOrFail();
    }


}
