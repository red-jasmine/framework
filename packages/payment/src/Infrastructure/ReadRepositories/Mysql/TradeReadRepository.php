<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\PaymentTrade;
use RedJasmine\Payment\Domain\Repositories\TradeReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class TradeReadRepository extends QueryBuilderReadRepository implements TradeReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = PaymentTrade::class;

}