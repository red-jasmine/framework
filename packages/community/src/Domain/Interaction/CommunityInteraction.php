<?php

namespace RedJasmine\Community\Domain\Interaction;


use RedJasmine\Interaction\Domain\Contracts\InteractionResourceInterface;
use RedJasmine\Interaction\Domain\Contracts\InteractionTypeLimiterConfigInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Types\BaseInteractionTypeLimiterConfig;

class CommunityInteraction implements InteractionResourceInterface
{


    /**
     * 支持的互动策略
     * @return array
     */
    public function allowInteractionType() : array
    {
        return config('red-jasmine-community.interaction.types', [
            'comment',
            'like',
            'dislike',
            'share',
            'view',
            'report',
            'favorite',
        ]);
    }

    public function validate(InteractionData $data) : bool
    {
        return true;
    }

    public function getInteractionTypeLimiterConfig(InteractionData $data) : InteractionTypeLimiterConfigInterface
    {
        $limiterConfig = config('red-jasmine-Community.interaction.limiter', []);
        return new BaseInteractionTypeLimiterConfig($limiterConfig[$data->interactionType] ?? $limiterConfig['default'] ?? []);
    }


}