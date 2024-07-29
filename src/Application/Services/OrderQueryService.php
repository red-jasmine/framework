<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Infrastructure\ReadRepositories\OrderReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

/**
 * @method Order find(int $id, array $query = [])
 */
class OrderQueryService extends ApplicationQueryService
{
    public function __construct(protected OrderReadRepositoryInterface $repository)
    {
        parent::__construct();
    }


    protected array $allowedFilters = [];


    protected array $allowedIncludes = [
        'products',
        'payments',
        'info',
        'products.info',
        'logistics',
        'address'
    ];
    protected array $allowedFields   = [];


    protected array $allowedSorts = [];


}
