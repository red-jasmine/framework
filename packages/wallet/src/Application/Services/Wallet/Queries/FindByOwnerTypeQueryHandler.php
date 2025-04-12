<?php

namespace RedJasmine\Wallet\Application\Services\Wallet\Queries;

use RedJasmine\Support\Application\Queries\QueryHandler;
use RedJasmine\Wallet\Application\Services\Wallet\WalletApplicationService;
use RedJasmine\Wallet\Domain\Models\Wallet;

class FindByOwnerTypeQueryHandler extends QueryHandler
{

    public function __construct(
        public WalletApplicationService $service
    ) {
    }

    public function handle(FindByOwnerTypeQuery $query) : ?Wallet
    {
        return $this->service->readRepository->findByOwnerType($query->owner, $query->type);
    }
}