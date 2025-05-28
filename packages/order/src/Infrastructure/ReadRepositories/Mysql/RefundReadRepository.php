<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Order\Domain\Repositories\RefundReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class RefundReadRepository extends QueryBuilderReadRepository implements RefundReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Refund::class;

    public function allowedIncludes() : array
    {
        return [
            'logistics',
            'order',
            'orderProduct',
            'payments'
        ];
    }

    public function findByNo(string $no) : Refund
    {
        return $this->query()->where('order_no', $no)->firstOrFail();
    }

}
