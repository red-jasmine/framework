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
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('type'),

        ];
    }


    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Vip::class;

    public function findVipType(string $biz, string $type) : ?Vip
    {

        return $this->query()->where('biz', $biz)->where('type', $type)->first();

    }


}