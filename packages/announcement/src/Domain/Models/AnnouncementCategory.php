<?php

namespace RedJasmine\Announcement\Domain\Models;

use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Presets\Category\Domain\Models\BaseCategoryModel;

/**
 * @property string $biz 业务标识
 */
class AnnouncementCategory extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
}
