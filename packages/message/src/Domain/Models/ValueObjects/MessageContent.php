<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Models\ValueObjects;

use Illuminate\Mail\Attachment;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 消息内容值对象
 */
class MessageContent extends ValueObject
{
    /**
     * 消息标题
     */
    public string $title;

    /**
     * 消息正文内容
     */
    public string $content;

    /**
     * 内容类型 (text, html, markdown)
     */
    /**
     * @var ContentTypeEnum
     */
    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::TEXT;

    /**
     * 附件信息
     */

    public array $attachments = [];

    /**
     * 附加数据
     * @var array
     */
    public array $data = [];


    /**
     * 是否包含附件
     */
    public function hasAttachments() : bool
    {
        return !empty($this->attachments);
    }

    /**
     * 获取附件数量
     */
    public function getAttachmentCount() : int
    {
        return count($this->attachments);
    }
}
