<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\PaymentChannelApp;
use RedJasmine\Payment\Domain\Repositories\ChannelAppReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class ChannelAppReadRepository extends QueryBuilderReadRepository implements ChannelAppReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = PaymentChannelApp::class;

}
