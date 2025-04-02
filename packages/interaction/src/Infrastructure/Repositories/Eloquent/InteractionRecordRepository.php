<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories\Eloquent;

use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class InteractionRecordRepository extends EloquentRepository implements InteractionRecordRepositoryInterface
{

    protected static string $eloquentModelClass = InteractionRecord::class;


}