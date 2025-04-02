<?php

namespace RedJasmine\Article\Domain\Interaction;


use RedJasmine\Interaction\Domain\Contracts\InteractionResourceInterface;
use RedJasmine\Interaction\Domain\Data\InteractionData;

class ArticleInteraction implements InteractionResourceInterface
{
    public const string TYPE = 'article';

    /**
     * 支持的互动策略
     * @return array
     */
    public function allowInteractionType() : array
    {
        return [
            'comment', 'like', 'dislike', 'share', 'view', 'report', 'favorite',
        ];
    }

    public function validate(InteractionData $data) : bool
    {
        return true;
    }


}