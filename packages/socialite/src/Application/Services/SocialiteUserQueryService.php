<?php

namespace RedJasmine\Socialite\Application\Services;

use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQuery;
use RedJasmine\Socialite\Application\Services\Queries\GetUsersByOwnerQueryHandler;
use RedJasmine\Socialite\Domain\Repositories\SocialiteUserReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Application\QueryHandlers\FindQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\PaginateQueryHandler;
use RedJasmine\Support\Application\QueryHandlers\SimplePaginateQueryHandler;

/**
 * @method getUsersByOwner(GetUsersByOwnerQuery $query)
 */
class SocialiteUserQueryService extends ApplicationQueryService
{

    public function __construct(
        public  SocialiteUserReadRepositoryInterface $repository

    ) {
    }

    protected static $macros = [
        'findById'        => FindQueryHandler::class,
        'paginate'        => PaginateQueryHandler::class,
        'simplePaginate'  => SimplePaginateQueryHandler::class,
        'getUsersByOwner' => GetUsersByOwnerQueryHandler::class
    ];


}