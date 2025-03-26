<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;


/**
 * @method ProductCategoryReadRepositoryInterface getRepository()
 */
class ProductCategoryQueryService extends ApplicationQueryService
{

    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.category.query';

    public function __construct(protected ProductCategoryReadRepositoryInterface $repository)
    {

    }


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('name'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('status'),

        ];
    }


    public function allowedFields() : array
    {
        return [
            'id',
            'parent_id',
            'name',
            'image',
            'group_name',
            'sort',
            'is_leaf',
            'is_show',
            'status',
            'extras',
        ];

    }


    public function tree(ProductCategoryTreeQuery $query) : array
    {

        return $this->getRepository()->tree($query);
    }


    public function isAllowUse(int $id) : bool
    {
        return (bool)($this->find(FindQuery::make($id))?->isAllowUse());
    }


    public function onlyShow() : void
    {
        $this->getRepository()->withQuery(function ($query) {
            $query->show();
        });
    }

}
