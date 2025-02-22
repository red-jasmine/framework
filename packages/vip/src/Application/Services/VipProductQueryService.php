<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Vip\Domain\Repositories\VipProductReadRepositoryInterface;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class VipProductQueryService extends ApplicationQueryService
{

    public function __construct(
        public VipProductReadRepositoryInterface $repository
    ) {
    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('app_id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('keyword'),

        ];
    }

}