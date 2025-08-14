<?php

declare(strict_types=1);

namespace RedJasmine\Message\Application\Services\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RedJasmine\Message\Application\Services\MessageApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

/**
 * 消息列表查询处理器
 */
class MessageListQueryHandler extends QueryHandler
{
    public function __construct(
        protected MessageApplicationService $service,
    ) {
    }

    /**
     * 处理消息列表查询
     */
    public function handle(MessageListQuery $query): LengthAwarePaginator
    {
        // 设置查询作用域
        $this->applyQueryScope($query);

        // 应用过滤条件
        $this->applyFilters($query);

        // 应用关联查询
        $this->applyIncludes($query);

        // 执行分页查询
        return $this->service->readRepository->paginate($query);
    }

    /**
     * 应用查询作用域
     */
    protected function applyQueryScope(MessageListQuery $query): void
    {
        $this->service->readRepository->withQuery(function ($builder) use ($query) {
            // 如果指定了所属者，只查询属于该用户的消息
            if ($query->getOwnerId()) {
                $builder->where('owner_id', $query->getOwnerId());
            }

            // 如果指定了接收人，只查询该接收人的消息
            if ($query->receiverId) {
                $builder->where('receiver_id', $query->receiverId);
            }

            // 特殊查询作用域
            if ($query->isUnreadOnly()) {
                $builder->unread();
            }

            if ($query->isHighPriorityOnly()) {
                $builder->highPriority();
            }

            if ($query->isUrgentOnly()) {
                $builder->urgent();
            }

            // 排除过期消息（除非明确查询过期消息）
            if ($query->isExpired === false) {
                $builder->notExpired();
            }
        });
    }

    /**
     * 应用过滤条件
     */
    protected function applyFilters(MessageListQuery $query): void
    {
        $filters = $query->getFilters();
        
        foreach ($filters as $key => $value) {
            $this->service->readRepository->withQuery(function ($builder) use ($key, $value) {
                $this->applyFilter($builder, $key, $value);
            });
        }
    }

    /**
     * 应用单个过滤条件
     */
    protected function applyFilter($builder, string $key, $value): void
    {
        switch ($key) {
            case 'created_between':
                $builder->whereBetween('created_at', $value);
                break;
                
            case 'read_between':
                $builder->whereBetween('read_at', $value);
                break;
                
            case 'expires_between':
                $builder->whereBetween('expires_at', $value);
                break;
                
            case 'expires_before':
                $builder->where('expires_at', '<', $value);
                break;
                
            case 'has_attachment':
                if ($value) {
                    $builder->whereJsonLength('data->attachments', '>', 0);
                } else {
                    $builder->whereJsonLength('data->attachments', 0);
                }
                break;
                
            case 'not_expired':
                $builder->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                });
                break;
                
            case 'high_priority':
                $builder->whereIn('priority', ['high', 'urgent']);
                break;
                
            case 'channel':
                $builder->whereJsonContains('channels', $value);
                break;
                
            default:
                $builder->where($key, $value);
                break;
        }
    }

    /**
     * 应用关联查询
     */
    protected function applyIncludes(MessageListQuery $query): void
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
    protected function validateQueryPermission(MessageListQuery $query): void
    {
        // 验证用户是否有权限查询指定的数据
        // 例如：检查业务线权限、分类权限等
        
        if ($query->biz) {
            $this->validateBizPermission($query->biz, $query->owner);
        }
        
        if ($query->categoryId) {
            $this->validateCategoryPermission($query->categoryId, $query->owner);
        }
    }

    /**
     * 验证业务线权限
     */
    protected function validateBizPermission(string $biz, $user): void
    {
        // 检查用户是否有权限访问指定业务线的消息
    }

    /**
     * 验证分类权限
     */
    protected function validateCategoryPermission(int $categoryId, $user): void
    {
        // 检查用户是否有权限访问指定分类的消息
    }
}
