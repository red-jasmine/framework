<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\MerchantChannelApp;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class MerchantAppReadRepository extends QueryBuilderReadRepository implements MerchantChannelAppReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = MerchantChannelApp::class;

}
