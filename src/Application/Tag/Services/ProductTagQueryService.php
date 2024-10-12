<?php

namespace RedJasmine\Product\Application\Tag\Services;

use RedJasmine\Product\Domain\Tag\Repositories\ProductTagReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;


class ProductTagQueryService extends ApplicationQueryService
{
    /**
     * 命令钩子前缀
     * @var string
     */
    public static string $hookNamePrefix = 'product.application.tag.query';


    public function __construct(protected ProductTagReadRepositoryInterface $repository)
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
            'is_public',
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
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
        ];
    }


}
