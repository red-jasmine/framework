<?php

namespace RedJasmine\Product\Application\Category\Services;

use RedJasmine\Product\Application\Category\UserCases\Queries\ProductSellerCategoryTreeQuery;
use RedJasmine\Product\Domain\Category\Repositories\ProductSellerCategoryReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Contracts\UserInterface;
use Spatie\QueryBuilder\AllowedFilter;


class ProductSellerCategoryQueryService extends ApplicationQueryService
{


    public function __construct(protected ProductSellerCategoryReadRepositoryInterface $repository)
    {
        parent::__construct();
    }

    public function allowedFields() : array
    {
        return [
            'id', 'parent_id', 'name',
            'image',
            'group_name', 'sort',
            'is_leaf', 'is_show',
            'status', 'expands',
        ];

    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),

        ];
    }

    public function tree(ProductSellerCategoryTreeQuery $query) : array
    {
        return $this->repository->tree($query);
    }


    public function isAllowUse(int $id, UserInterface $owner) : bool
    {

        return (bool)($this->withQuery(function ($query) use ($owner) {
            return $query->onlyOwner($owner);
        })->find($id)?->isAllowUse());
    }


}
