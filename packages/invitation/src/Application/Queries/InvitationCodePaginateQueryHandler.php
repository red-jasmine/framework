<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Application\Queries;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Invitation\Application\Services\InvitationCodeApplicationService;

/**
 * 邀请码分页查询处理器
 */
final class InvitationCodePaginateQueryHandler
{
    public function __construct(
        protected InvitationCodeApplicationService $service
    ) {
    }

    /**
     * 处理分页查询
     */
    public function handle(InvitationCodePaginateQuery $query): LengthAwarePaginator|Paginator
    {
        // 使用标准的paginate方法（基类提供）
        return $this->service->readRepository->paginate($query);
    }
} 