<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Payment\Domain\Models\ChannelMerchant;
use RedJasmine\Payment\Domain\Repositories\ChannelMerchantReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ChannelMerchantReadRepository extends QueryBuilderReadRepository implements ChannelMerchantReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = ChannelMerchant::class;

}
