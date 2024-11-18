<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentMerchant;
use RedJasmine\Payment\Domain\Repositories\MerchantRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class MerchantRepository extends EloquentRepository implements MerchantRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentMerchant::class;


}
