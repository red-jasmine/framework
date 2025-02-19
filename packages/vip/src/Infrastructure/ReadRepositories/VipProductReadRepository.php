<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Vip\Domain\Models\VipProduct;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;

class VipProductReadRepository extends QueryBuilderReadRepository implements VipProductReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = VipProduct::class;


}