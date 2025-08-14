<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 推送渠道枚举
 */
enum PushChannelEnum: string
{
    use EnumsHelper;

    case IN_APP = 'in_app';
    case PUSH = 'push';
    case EMAIL = 'email';
    case SMS = 'sms';

    public static function labels(): array
    {
        return [
            self::IN_APP->value => 'APP内消息',
            self::PUSH->value => '推送通知',
            self::EMAIL->value => '邮件',
            self::SMS->value => '短信',
        ];
    }

    public static function colors(): array
    {
        return [
            self::IN_APP->value => 'primary',
            self::PUSH->value => 'success',
            self::EMAIL->value => 'info',
            self::SMS->value => 'warning',
        ];
    }

    public static function icons(): array
    {
        return [
            self::IN_APP->value => 'heroicon-o-chat-bubble-left-right',
            self::PUSH->value => 'heroicon-o-device-phone-mobile',
            self::EMAIL->value => 'heroicon-o-envelope',
            self::SMS->value => 'heroicon-o-chat-bubble-left-ellipsis',
        ];
    }

    /**
     * 是否为实时推送渠道
     */
    public function isRealtime(): bool
    {
        return match ($this) {
            self::IN_APP, self::PUSH => true,
            self::EMAIL, self::SMS => false,
        };
    }

    /**
     * 是否支持富文本
     */
    public function supportsRichText(): bool
    {
        return match ($this) {
            self::IN_APP, self::EMAIL => true,
            self::PUSH, self::SMS => false,
        };
    }
}
