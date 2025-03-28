<?php

namespace RedJasmine\Product\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\BaseReadRepository;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class BrandReadRepository extends QueryBuilderReadRepository implements BrandReadRepositoryInterface
{

    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Brand::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('initial'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('status'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('english_name'),
            AllowedFilter::callback('search', static function (Builder $builder, $value) {
                return $builder->where(function (Builder $builder) use ($value) {
                    $builder->where('name', 'like', '%'.$value.'%')->orWhere('english_name', 'like', '%'.$value.'%');
                });
            })

        ];
    }
}
