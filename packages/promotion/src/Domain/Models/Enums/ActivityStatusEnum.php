<?php

namespace RedJasmine\Promotion\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ActivityStatusEnum: string
{
    use EnumsHelper;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case WARMING = 'warming';
    case RUNNING = 'running';
    case PAUSED = 'paused';
    case ENDED = 'ended';
    case CANCELLED = 'cancelled';

    public static function labels() : array
    {
        return [
            self::DRAFT->value     => '草稿',
            self::PENDING->value   => '待审核',
            self::PUBLISHED->value => '已发布',
            self::WARMING->value   => '预热中',
            self::RUNNING->value   => '进行中',
            self::PAUSED->value    => '已暂停',
            self::ENDED->value     => '已结束',
            self::CANCELLED->value => '已取消',
        ];
    }

    public static function colors() : array
    {
        return [
            self::DRAFT->value     => 'gray',
            self::PENDING->value   => 'yellow',
            self::PUBLISHED->value => 'blue',
            self::WARMING->value   => 'orange',
            self::RUNNING->value   => 'green',
            self::PAUSED->value    => 'red',
            self::ENDED->value     => 'gray',
            self::CANCELLED->value => 'red',
        ];
    }

    public static function icons() : array
    {
        return [
            self::DRAFT->value     => 'heroicon-o-document-text',
            self::PENDING->value   => 'heroicon-o-clock',
            self::PUBLISHED->value => 'heroicon-o-eye',
            self::WARMING->value   => 'heroicon-o-fire',
            self::RUNNING->value   => 'heroicon-o-play',
            self::PAUSED->value    => 'heroicon-o-pause',
            self::ENDED->value     => 'heroicon-o-stop',
            self::CANCELLED->value => 'heroicon-o-x-circle',
        ];
    }
}