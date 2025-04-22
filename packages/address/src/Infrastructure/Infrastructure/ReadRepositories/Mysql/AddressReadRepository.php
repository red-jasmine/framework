<?php

namespace RedJasmine\Address\Infrastructure\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Address\Domain\Models\Address;
use RedJasmine\Address\Domain\Repositories\AddressReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class AddressReadRepository extends QueryBuilderReadRepository implements AddressReadRepositoryInterface
{

    public static string $modelClass = Address::class;


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),

        ];
    }


}