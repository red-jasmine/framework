<?php

namespace RedJasmine\Vip\Infrastructure\ReadRepositories;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\Vip\Domain\Models\Vip;
use RedJasmine\Vip\Domain\Repositories\VipReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class VipReadRepository extends QueryBuilderReadRepository implements VipReadRepositoryInterface
{

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('app_id'),
            AllowedFilter::exact('type'),

        ];
    }


    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Vip::class;

    public function findVipType(string $appId, string $type) : ?Vip
    {

        return $this->query()->where('app_id', $appId)->where('type', $type)->first();

    }


}