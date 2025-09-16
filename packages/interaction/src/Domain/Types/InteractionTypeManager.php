<?php

namespace RedJasmine\Interaction\Domain\Types;

use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Support\Helpers\Services\ServiceManager;

/**
 * @method InteractionTypeInterface create(string $name)
 */
class InteractionTypeManager extends ServiceManager
{
    protected const  PROVIDERS = [
        'like'    => LikeInteractionType::class,
        'comment' => CommentInteractionType::class,
    ];
}