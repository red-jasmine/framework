<?php

namespace RedJasmine\Product\Application\Service\Services;

use RedJasmine\Product\Domain\Service\Repositories\ProductServiceReadRepositoryInterface;
use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;


class ProductServiceQueryService extends ApplicationQueryService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.service.query';


    public function __construct(protected ProductServiceReadRepositoryInterface $repository)
    {

    }

    public function allowedFields() : array
    {
        return [
            'id',
            'name',
            'description',
            'icon',
            'cluster',
            'sort',

            'is_show',
            'status',

        ];

    }

    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::exact('is_public'),
            AllowedFilter::exact('cluster'),

        ];
    }


}
