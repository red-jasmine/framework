<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\MerchantChannelApp;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class MerchantChannelAppRepository extends EloquentRepository implements MerchantChannelAppRepositoryInterface
{

    protected static string $eloquentModelClass = MerchantChannelApp::class;


}
