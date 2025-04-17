<?php

namespace RedJasmine\Address\Infrastructure\Repositories\Eloquent;

use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class AddressReadRepository extends EloquentRepository implements AddressRepositoryInterface
{

    protected static string $eloquentModelClass = Address::class;

}