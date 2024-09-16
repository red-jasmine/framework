<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Infrastructure\ReadRepositories\OrderReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


/**
 *
 * @method  Order find($id, ?FindQuery $findQuery = null)
 */
class OrderReadRepository extends QueryBuilderReadRepository implements OrderReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Order::class;


}
