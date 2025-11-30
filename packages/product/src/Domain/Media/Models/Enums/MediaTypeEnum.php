<?php

namespace RedJasmine\Product\Domain\Media\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum MediaTypeEnum: string
{
    use EnumsHelper;

    case IMAGE = 'image'; // 图片
    case VIDEO = 'video'; // 视频
    case DOCUMENT = 'document'; // 文档
    case MODEL_3D = '3d_model'; // 3D模型

    public static function labels(): array
    {
        return [
            self::IMAGE->value => '图片',
            self::VIDEO->value => '视频',
            self::DOCUMENT->value => '文档',
            self::MODEL_3D->value => '3D模型',
        ];
    }

    public static function colors(): array
    {
        return [
            self::IMAGE->value => 'primary',
            self::VIDEO->value => 'info',
            self::DOCUMENT->value => 'warning',
            self::MODEL_3D->value => 'success',
        ];
    }

    public static function icons(): array
    {
        return [
            self::IMAGE->value => 'heroicon-o-photo',
            self::VIDEO->value => 'heroicon-o-video-camera',
            self::DOCUMENT->value => 'heroicon-o-document',
            self::MODEL_3D->value => 'heroicon-o-cube',
        ];
    }
}

