<?php

namespace RedJasmine\Distribution\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Repositories\PromoterReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class PromoterReadRepository extends QueryBuilderReadRepository implements PromoterReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Promoter::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('level'),
            AllowedFilter::exact('group_id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('team_id'),
            AllowedFilter::partial('owner_id'),
            AllowedFilter::callback('search', static function (Builder $builder, $value) {
                return $builder->where(function (Builder $builder) use ($value) {
                    $builder->where('name', 'like', '%'.$value.'%')
                           ->orWhere('owner_id', 'like', '%'.$value.'%');
                });
            })
        ];
    }
}