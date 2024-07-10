<?php

namespace RedJasmine\Product\Application\Brand\Services;

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
            AllowedFilter::partial('name'),
            AllowedFilter::partial('english_name'),

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
