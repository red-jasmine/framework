<?php

namespace RedJasmine\Announcement\Domain\Data;

use RedJasmine\Announcement\Domain\Models\Enums\AnnouncementStatus;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Models\Enums\ContentTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class AnnouncementData extends Data
{
    public UserInterface $owner;
    public string        $biz;
    public ?int          $categoryId = null;
    public string        $title;
    public ?string       $image      = null;

    #[WithCast(EnumCast::class, ContentTypeEnum::class)]
    public ContentTypeEnum $contentType = ContentTypeEnum::TEXT;
    public string          $content;

    public array   $scopes      = [];
    public array   $channels    = [];
    public ?string $publishTime = null;

    #[WithCast(EnumCast::class, AnnouncementStatus::class)]
    public AnnouncementStatus $status = AnnouncementStatus::DRAFT;

    public array $attachments = [];
    public bool  $isForceRead = false;
}
