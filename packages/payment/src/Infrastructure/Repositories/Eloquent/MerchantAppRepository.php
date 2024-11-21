<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;
use RedJasmine\Payment\Domain\Repositories\MerchantAppRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class MerchantAppRepository extends EloquentRepository implements MerchantAppRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentMerchantApp::class;


}