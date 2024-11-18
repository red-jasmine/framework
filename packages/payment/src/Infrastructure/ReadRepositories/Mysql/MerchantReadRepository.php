<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Payment\Domain\Models\PaymentMerchant;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class MerchantReadRepository extends QueryBuilderReadRepository implements ReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = PaymentMerchant::class;

}
