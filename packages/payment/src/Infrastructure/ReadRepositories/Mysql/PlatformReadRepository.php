<?php

namespace RedJasmine\Payment\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Payment\Domain\Models\Platform;
use RedJasmine\Payment\Domain\Repositories\PlatformReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class PlatformReadRepository extends QueryBuilderReadRepository implements PlatformReadRepositoryInterface
{

    /**
     *
     * @var $modelClass class-string
     */
    protected static string $modelClass = Platform::class;

    /**
     * @param Query $query
     * @return Platform|null
     */
    public function findByCode(Query $query) : ?Platform
    {
        $code = $query->code;
        return $this->query($query->except('id'))->where('code', $code)->firstOrFail();
    }


}
