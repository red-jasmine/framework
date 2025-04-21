<?php

namespace RedJasmine\Address\Application\Services;

use RedJasmine\Address\Application\Services\Hooks\AddressRegionHook;
use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressReadRepositoryInterface;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class AddressApplicationService extends ApplicationService
{

    protected static string $modelClass = Address::class;

    public static string $hookNamePrefix = 'address.application.address';

    public function __construct(
        public AddressRepositoryInterface $repository,
        public AddressReadRepositoryInterface $readRepository,
    ) {
    }

    protected function hooks() : array
    {
        return [
            'create.validate' => [
                AddressRegionHook::class
            ],
            'update.validate' => [
                AddressRegionHook::class
            ]
        ];
    }
}