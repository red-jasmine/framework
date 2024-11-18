<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class MerchantAppReadRepository extends QueryBuilderReadRepository implements MerchantAppReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = PaymentMerchantApp::class;

}
