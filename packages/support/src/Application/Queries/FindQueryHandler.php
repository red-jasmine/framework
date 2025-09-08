<?php

namespace RedJasmine\Support\Application\Queries;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;

/**
 * @property ApplicationService $service
 */
class FindQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }

    public function handle(FindQuery $query) : mixed
    {
        if (in_array(UniqueNoInterface::class, class_implements($this->service->getModelClass()))) {
            if (!$this->service->getModelClass()::checkUniqueNo($query->getKey())) {
                throw new ModelNotFoundException('not found!!!');
            }
            $query->setPrimaryKey($this->service->getModelClass()::getUniqueNoKey());
        }
        return $this->service->repository->findByQuery($query);
    }
}
