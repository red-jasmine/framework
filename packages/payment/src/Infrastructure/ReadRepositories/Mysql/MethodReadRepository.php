<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\Method;
use RedJasmine\Payment\Domain\Repositories\MethodReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class MethodReadRepository extends QueryBuilderReadRepository implements MethodReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Method::class;

    /**
     * @param Query $query
     * @return Method|null
     */
    public function findByCode(Query $query) : ?Method
    {
        $code = $query->code;
        return $this->query()->where('code', $code)->firstOrFail();
    }


}
