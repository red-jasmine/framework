<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Vip\Domain\Models\UserVipOrder;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderReadRepositoryInterface;

class UserVipOrderReadRepository extends QueryBuilderReadRepository implements UserVipOrderReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = UserVipOrder::class;


}