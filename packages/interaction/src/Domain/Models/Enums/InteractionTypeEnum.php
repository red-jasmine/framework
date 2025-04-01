<?php

namespace RedJasmine\Interaction\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum InteractionTypeEnum: string
{
    use EnumsHelper;

    case FOLLOW = 'follow';
    case COMMENT = 'comment';
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case SHARE = 'share';
    case REPORT = 'report';
    case FAVORITE = 'favorite';
    case VIEW = 'view';
    case REWARD = 'reward';
    case VOTE = 'vote';


    public static function labels() : array
    {
        return [
            self::COMMENT->value  => '评论',
            self::LIKE->value     => '点赞',
            self::DISLIKE->value  => '点踩',
            self::SHARE->value    => '分享',
            self::REPORT->value   => '举报',
            self::FAVORITE->value => '收藏',
            self::VIEW->value     => '查看',
            self::VOTE->value     => '投票',
            self::REWARD->value   => '打赏',
            self::FOLLOW->value   => '关注',
        ];
    }

}
