<?php

namespace RedJasmine\Address\Application\Services;

use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressReadRepositoryInterface;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class AddressApplicationService extends ApplicationService
{

    protected static string $modelClass = Address::class;

    public function __construct(
        public AddressRepositoryInterface $repository,
        public AddressReadRepositoryInterface $readRepository,
    ) {
    }

}