<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class MerchantAppRepository extends Repository implements MerchantAppRepositoryInterface
{

    protected static string $modelClass = MerchantApp::class;


}
