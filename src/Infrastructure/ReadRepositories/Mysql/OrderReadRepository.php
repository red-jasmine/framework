<?php

namespace RedJasmine\Order\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Infrastructure\ReadRepositories\OrderReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;


class OrderReadRepository extends QueryBuilderReadRepository implements OrderReadRepositoryInterface
{


    /**
     * @var $modelClass class-string
     */
    protected string $modelClass = Order::class;


    public function findAll(array $query = []) : LengthAwarePaginator
    {
        return $this->query($query)->paginate();
    }


    public function findById($id, array $query = []) : Order
    {
        return $this->query($query)->findOrFail($id);
    }


}
