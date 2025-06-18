<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Distribution\Domain\Models\PromoterOrder;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterOrderReadRepository extends QueryBuilderReadRepository implements PromoterOrderReadRepositoryInterface
{
    protected static string $modelClass = PromoterOrder::class;

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_no'),
            AllowedFilter::exact('promoter_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('payment_status'),
        ];
    }
}