<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Queries;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * 邀请码查找查询
 */
final class InvitationCodeFindQuery extends FindQuery
{
    public ?string $code = null;
} 