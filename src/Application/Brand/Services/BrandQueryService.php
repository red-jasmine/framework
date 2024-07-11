<?php

namespace RedJasmine\Product\Application\Brand\Services;


use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Product\Domain\Brand\Repositories\BrandReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @method Brand find(int $id, ?FindQuery $query = null)
 */
class BrandQueryService extends ApplicationQueryService
{
    public function __construct(protected BrandReadRepositoryInterface $repository)
    {
        parent::__construct();
    }


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
                    $builder->where('name', 'like', '%' . $value . '%')
                            ->orWhere('english_name', 'like', '%' . $value . '%');
                });
            })

        ];
    }

    public function isAllowUse(int $id) : bool
    {
        return (bool)($this->find($id)?->isAllowUse());
    }


    public function onlyShow() : void
    {
        $this->withQuery(function ($query) {
            $query->show();
        });
    }
}
