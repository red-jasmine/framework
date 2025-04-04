<?php

namespace RedJasmine\Interaction\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface InteractionRecordRepositoryInterface extends RepositoryInterface
{


    public function findByInteractionType(string $interactionType, $id);

}