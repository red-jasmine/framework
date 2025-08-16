<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Message\Domain\Models\Enums\BizEnum;
use RedJasmine\Message\Domain\Models\Enums\StatusEnum;
use RedJasmine\Support\Domain\Data\BaseCategoryData;
use RedJasmine\Support\Domain\Models\BaseCategoryModel;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;

/**
 * 消息分类聚合根
 */
class MessageCategory extends BaseCategoryModel
{

}
