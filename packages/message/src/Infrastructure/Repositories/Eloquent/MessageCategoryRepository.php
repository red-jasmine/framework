<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Infrastructure\Repositories\Eloquent;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 消息分类仓库实现
 */
class MessageCategoryRepository extends Repository implements MessageCategoryRepositoryInterface
{
    protected static string $modelClass = MessageCategory::class;


}
