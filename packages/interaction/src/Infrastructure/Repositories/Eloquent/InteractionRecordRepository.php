<?php

namespace RedJasmine\Interaction\Infrastructure\Repositories\Eloquent;

use RedJasmine\Interaction\Domain\Facades\InteractionType;
use RedJasmine\Interaction\Domain\Models\InteractionRecord;
use RedJasmine\Interaction\Domain\Repositories\InteractionRecordRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class InteractionRecordRepository extends Repository implements InteractionRecordRepositoryInterface
{

    protected static string $modelClass = InteractionRecord::class;

    public function findByInteractionType(string $interactionType, $id)
    {
        $interactionType    = InteractionType::create($interactionType);
        static::$modelClass = $interactionType->getModelClass();
        return parent::find($id);
    }


}