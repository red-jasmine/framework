<?php

namespace RedJasmine\Article\Domain\Interaction;

use RedJasmine\Interaction\Domain\Contracts\InteractionResourceStrategyInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;
use RedJasmine\Interaction\Domain\Models\Enums\InteractionTypeEnum;

class ArticleInteractionStrategy implements InteractionResourceStrategyInterface
{
    public const string TYPE = 'article';

    /**
     * 支持的互动策略
     * @return array|InteractionTypeEnum[]
     */
    public function allowInteractionType() : array
    {
        return [
            InteractionTypeEnum::COMMENT,
            InteractionTypeEnum::FAVORITE,
            InteractionTypeEnum::REPORT,

            InteractionTypeEnum::LIKE,
            InteractionTypeEnum::DISLIKE,
            InteractionTypeEnum::SHARE,
            InteractionTypeEnum::VIEW,

        ];
    }

    public function validate(InteractionData $data) : bool
    {
        return true;
    }


}