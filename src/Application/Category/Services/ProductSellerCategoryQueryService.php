<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use Spatie\QueryBuilder\AllowedFilter;


class ProductSellerCategoryQueryService extends ApplicationQueryService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.seller-category.query';


    public function __construct(protected ProductSellerCategoryReadRepositoryInterface $repository)
    {

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
            'expands',
        ];

    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_leaf'),
            AllowedFilter::exact('group_name'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
        ];
    }

    public function tree(ProductSellerCategoryTreeQuery $query) : array
    {

        return $this->repository
            ->tree($query);
    }

    public function onlyShow() : void
    {
        $this->getRepository()->withQuery(function ($query) {
            $query->show();
        });
    }


    public function isAllowUse(int $id, UserInterface $owner) : bool
    {

        return (bool) ($this->getRepository()->withQuery(function ($query) use ($owner) {
            return $query->onlyOwner($owner);
        })->findById(FindQuery::make($id))?->isAllowUse());
    }


}
