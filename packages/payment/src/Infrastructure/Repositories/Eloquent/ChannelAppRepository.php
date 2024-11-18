<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ChannelAppRepository extends EloquentRepository implements ChannelAppRepositoryInterface
{

    protected static string $eloquentModelClass = PaymentChannelApp::class;


}
