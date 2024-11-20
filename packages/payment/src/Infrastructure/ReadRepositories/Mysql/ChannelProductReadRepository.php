<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\PaymentChannelProduct;
use RedJasmine\Payment\Domain\Repositories\ChannelProductReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ChannelProductReadRepository extends QueryBuilderReadRepository implements ChannelProductReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = PaymentChannelProduct::class;

}
