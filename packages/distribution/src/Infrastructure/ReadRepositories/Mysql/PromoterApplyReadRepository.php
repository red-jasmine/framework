<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Distribution\Domain\Models\PromoterApply;
use RedJasmine\Distribution\Domain\Repositories\PromoterApplyReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterApplyReadRepository extends QueryBuilderReadRepository implements PromoterApplyReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = PromoterApply::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('promoter_id'),
            AllowedFilter::exact('level'),
            AllowedFilter::exact('approval_method'),
            AllowedFilter::exact('approval_status'),
        ];
    }
}