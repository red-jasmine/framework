<?php

namespace RedJasmine\Community\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TopicStatusEnum: string
{
    use EnumsHelper;

    case PUBLISH = 'publish';
    case DRAFT = 'draft';
    case ARCHIVE = 'archive';


    public static function labels() : array
    {

        return [
            self::PUBLISH->value => '发布',
            self::DRAFT->value   => '草稿',
            self::ARCHIVE->value => '归档',

        ];
    }
}
