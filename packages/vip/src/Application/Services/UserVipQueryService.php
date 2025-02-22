<?php

namespace RedJasmine\Vip\Application\Services;

use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\SimplePaginateQueryHandler;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQuery;
use RedJasmine\Vip\Application\Services\Queries\FindUserVipQueryHandle;
use RedJasmine\Vip\Domain\Models\UserVip;
use RedJasmine\Vip\Domain\Repositories\UserVipReadRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @see FindUserVipQueryHandle::handle()
 * @method UserVip findUserVip(FindUserVipQuery $query)
 * @method UserVipReadRepositoryInterface getRepository()
 */
class UserVipQueryService extends ApplicationQueryService
{
    public function __construct(
        protected UserVipReadRepositoryInterface $repository

    ) {
    }

    protected static $macros = [
        'findById'       => FindQueryHandler::class,
        'paginate'       => PaginateQueryHandler::class,
        'simplePaginate' => SimplePaginateQueryHandler::class,
        'findUserVip'    => FindUserVipQueryHandle::class
    ];


    public function allowedIncludes() : array
    {
        return  ['vip'];
    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('app_id'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('id'),
            AllowedFilter::exact('keyword'),

        ];
    }


}