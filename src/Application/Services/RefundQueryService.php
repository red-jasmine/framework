<?php

namespace RedJasmine\Order\Application\Services;

use RedJasmine\Order\Infrastructure\ReadRepositories\RefundReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;

class RefundQueryService extends ApplicationQueryService
{
    public function __construct(protected RefundReadRepositoryInterface $repository)
    {
        parent::__construct();
    }

    // 每个查询
    protected array $allowedFilters = [];


    protected array $allowedIncludes = [
        'logistics',
        'order',
        'orderProduct',
        'payments'

    ];
    protected array $allowedFields   = [];


    protected array $allowedSorts = [];


}
