<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ChannelAppRepository extends Repository implements ChannelAppRepositoryInterface
{

    protected static string $modelClass = ChannelApp::class;


}

