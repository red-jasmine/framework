<?php

namespace RedJasmine\Support\Application\Queries;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;
use RedJasmine\Support\Helpers\ID\NoCheckNumber;

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
        if (in_array(UniqueNoInterface::class, class_implements($this->service->model()))) {
            if (NoCheckNumber::chack($query->getKey())) {
                $query->setPrimaryKey($this->service->model()::getUniqueNoKey());
            }
        }

        return $this->service->readRepository->find($query);
    }
}
