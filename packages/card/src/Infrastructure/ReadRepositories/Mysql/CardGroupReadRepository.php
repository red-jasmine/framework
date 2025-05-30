<?php
namespace RedJasmine\Card\Infrastructure\ReadRepositories\Mysql;



use RedJasmine\Card\Domain\Models\Card;
use RedJasmine\Card\Domain\Models\CardGroup;
use RedJasmine\Card\Domain\Repositories\CardGroupReadRepositoryInterface;
use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;

class CardGroupReadRepository extends QueryBuilderReadRepository implements CardGroupReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CardGroup::class;


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::partial('name'),
        ];
    }
}
