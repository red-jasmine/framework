<?php

namespace RedJasmine\User\Application\Services;

use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Application\Queries\FindQueryHandler;
use RedJasmine\Support\Application\Queries\PaginateQueryHandler;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQuery;
use RedJasmine\User\Application\Services\Queries\GetSocialitesQueryHandler;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;

/**
 * @method getSocialites(GetSocialitesQuery $query)
 */
class UserQueryService extends ApplicationQueryService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.brand.query';

    public function __construct(
        public UserReadRepositoryInterface $repository

    ) {
    }

    protected static $macros = [
        'findById'      => FindQueryHandler::class,
        'paginate'      => PaginateQueryHandler::class,
        'getSocialites' => GetSocialitesQueryHandler::class
    ];


}