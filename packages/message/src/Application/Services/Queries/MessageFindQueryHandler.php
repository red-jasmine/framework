<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 查找消息查询处理器
 */
class MessageFindQueryHandler extends QueryHandler
{
    public function __construct(
        protected MessageApplicationService $service,
    ) {
    }

    /**
     * 处理查找消息查询
     */
    public function handle(MessageFindQuery $query): ?Message
    {
        // 验证查询权限
        if ($query->shouldCheckPermission()) {
            $this->validateQueryPermission($query);
        }

        // 应用关联查询
        $this->applyIncludes($query);

        // 查找消息
        $message = $this->service->readRepository->find($query);

        // 检查访问权限
        if ($message && $query->shouldCheckPermission()) {
            $this->validateAccessPermission($message, $query);
        }

        return $message;
    }

    /**
     * 应用关联查询
     */
    protected function applyIncludes(MessageFindQuery $query): void
    {
        $includes = $query->getIncludes();
        
        if (!empty($includes)) {
            $this->service->readRepository->withQuery(function ($builder) use ($includes) {
                $builder->with($includes);
            });
        }
    }

    /**
     * 验证查询权限
     */
    protected function validateQueryPermission(MessageFindQuery $query): void
    {
        // 验证用户是否有权限查询消息
        if ($query->owner && $query->getOwnerId()) {
            // 可以添加基于所属者的权限检查
        }
    }

    /**
     * 验证访问权限
     */
    protected function validateAccessPermission(Message $message, MessageFindQuery $query): void
    {
        // 检查用户是否可以访问该消息
        if ($query->owner) {
            if (!$message->canAccess($query->owner)) {
                throw new \InvalidArgumentException('没有权限访问此消息');
            }

            // 检查消息是否过期
            if ($message->isExpired()) {
                throw new \InvalidArgumentException('消息已过期');
            }
        }
    }
}
