<?php

namespace RedJasmine\Card\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CardReadRepository extends QueryBuilderReadRepository implements CardReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Card::class;

    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('is_loop'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('group_id'),
        ];
    }

    public function allowedIncludes() : array
    {
        return ['group'];
    }

}
