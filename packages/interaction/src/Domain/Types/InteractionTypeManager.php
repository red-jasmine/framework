<?php

namespace RedJasmine\Interaction\Domain\Types;

use RedJasmine\Interaction\Domain\Contracts\InteractionTypeInterface;
use RedJasmine\Support\Helpers\Services\ServiceManager;

/**
 * @method InteractionTypeInterface create(string $name)
 */
class InteractionTypeManager extends ServiceManager
{
    protected const array PROVIDERS = [
        'like'    => LikeInteractionType::class,
        'dislike' => LikeInteractionType::class,
        'comment' => CommentInteractionType::class,
    ];
}