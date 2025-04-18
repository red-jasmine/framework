<?php

namespace RedJasmine\Region\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CountryReadRepository extends QueryBuilderReadRepository implements CountryReadRepositoryInterface
{
    use HasTree;

    public static string $modelClass = Country::class;


    protected mixed $defaultSort = 'code';


    public function allowedFilters() : array
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('region'),
            AllowedFilter::exact('native'),
            AllowedFilter::exact('currency'),
            AllowedFilter::exact('phone_code'),
        ];
    }
}