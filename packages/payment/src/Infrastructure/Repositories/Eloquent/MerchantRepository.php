<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\Merchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MerchantRepository extends Repository implements MerchantRepositoryInterface
{

    protected static string $modelClass = Merchant::class;


}
