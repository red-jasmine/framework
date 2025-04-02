<?php

namespace RedJasmine\Interaction\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class InteractionRecordReadRepository extends QueryBuilderReadRepository
    implements InteractionRecordReadRepositoryInterface
{
    public $modelClass = InteractionRecord::class;
}