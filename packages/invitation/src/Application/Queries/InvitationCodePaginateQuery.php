<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

/**
 * 邀请码分页查询
 */
final class InvitationCodePaginateQuery extends PaginateQuery
{
    public ?string $inviterType = null;
    public ?int $inviterId = null;
    public ?string $status = null;
    public ?string $code = null;
    public ?string $title = null;
    public ?string $generateType = null;
    public ?string $createdFrom = null;
    public ?string $createdTo = null;
} 