<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Distribution\Domain\Models\PromoterGroup;
use RedJasmine\Distribution\Domain\Repositories\PromoterGroupReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterGroupReadRepository extends QueryBuilderReadRepository implements PromoterGroupReadRepositoryInterface
{
    use HasTree;
    
    protected static string $modelClass = PromoterGroup::class;

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('status'),
        ];
    }
}