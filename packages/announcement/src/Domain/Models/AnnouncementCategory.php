<?php

namespace RedJasmine\Announcement\Domain\Models;

use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

/**
 * @property string $biz 业务标识
 */
class AnnouncementCategory extends BaseCategoryModel implements OwnerInterface
{
    use HasOwner;
}
