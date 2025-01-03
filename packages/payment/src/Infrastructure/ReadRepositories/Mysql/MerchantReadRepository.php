<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class MerchantReadRepository extends QueryBuilderReadRepository implements MerchantReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Merchant::class;

}
