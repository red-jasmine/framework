<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息只读仓库实现
 */
class MessageReadRepository extends QueryBuilderReadRepository implements MessageReadRepositoryInterface
{
    public static string $modelClass = Message::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters() : array
    {
        return [
            // 精确匹配
            AllowedFilter::exact('id'),
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('receiver_id'),
            AllowedFilter::exact('sender_id'),
            AllowedFilter::exact('template_id'),
            AllowedFilter::exact('source'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('priority'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('push_status'),
            AllowedFilter::exact('is_urgent'),
            AllowedFilter::exact('is_burn_after_read'),

            // 部分匹配
            AllowedFilter::partial('title'),
            AllowedFilter::partial('content'),

            // 使用模型作用域
            AllowedFilter::scope('unread'),
            AllowedFilter::scope('read'),
            AllowedFilter::scope('high_priority'),
            AllowedFilter::scope('urgent'),
            AllowedFilter::scope('not_expired'),
            AllowedFilter::partial('search', 'title'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),

            // 自定义回调
            AllowedFilter::callback('created_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$start, $end] = $value;
                    return $query->whereBetween('created_at', [$start, $end]);
                }
                return $query;
            }),

            AllowedFilter::callback('expires_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$start, $end] = $value;
                    return $query->whereBetween('expires_at', [$start, $end]);
                }
                return $query;
            }),

            AllowedFilter::callback('read_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$start, $end] = $value;
                    return $query->whereBetween('read_at', [$start, $end]);
                }
                return $query;
            }),

            AllowedFilter::callback('has_attachment', function ($query, $value) {
                if ($value) {
                    return $query->whereJsonLength('data->attachments', '>', 0);
                }
                return $query->whereJsonLength('data->attachments', 0);
            }),

            AllowedFilter::callback('channel', function ($query, $value) {
                return $query->whereJsonContains('channels', $value);
            }),
        ];
    }

    /**
     * 允许的排序字段配置
     */
    public function allowedSorts() : array
    {
        return [
            // 字段排序
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('read_at'),
            AllowedSort::field('expires_at'),
            AllowedSort::field('priority'),

            // 自定义排序
            AllowedSort::callback('priority_value', function ($query, $descending) {
                // 按优先级数值排序：URGENT=4, HIGH=3, NORMAL=2, LOW=1
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->orderByRaw("
                    CASE priority 
                        WHEN 'urgent' THEN 4
                        WHEN 'high' THEN 3
                        WHEN 'normal' THEN 2
                        WHEN 'low' THEN 1
                        ELSE 0
                    END {$orderDirection}
                ");
            }),

            AllowedSort::callback('status_order', function ($query, $descending) {
                // 按状态排序：UNREAD=3, READ=2, ARCHIVED=1
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->orderByRaw("
                    CASE status 
                        WHEN 'unread' THEN 3
                        WHEN 'read' THEN 2
                        WHEN 'archived' THEN 1
                        ELSE 0
                    END {$orderDirection}
                ");
            }),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes() : array
    {
        return [
            AllowedInclude::relationship('category'),
            AllowedInclude::relationship('template'),
            AllowedInclude::relationship('pushLogs'),
            AllowedInclude::relationship('category.parent'),
        ];
    }

    /**
     * 自定义查询方法：根据接收人查找消息列表
     */
    public function findByReceiver(string $receiverId, int $limit = 20) : Collection
    {
        return $this->query()
                    ->where('receiver_id', $receiverId)
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * 获取未读消息数量
     */
    public function getUnreadCount(UserInterface $owner, string $biz) : int
    {
        $query = $this->query()
                      ->where('biz', $biz)
                      ->where('status', MessageStatusEnum::UNREAD)
                      ->onlyOwner($owner);

        return $query->count();
    }

    // 简化的接口方法实现
    public function findList(array $ids) : Collection
    {
        return $this->query()->whereIn('id', $ids)->get();
    }

    public function getUnreadCountByBiz(string $receiverId) : array
    {
        return $this->query()
                    ->where('receiver_id', $receiverId)
                    ->where('status', 'unread')
                    ->selectRaw('biz, COUNT(*) as count')
                    ->groupBy('biz')
                    ->get()
                    ->pluck('count', 'biz')
                    ->toArray();
    }

    public function getUnreadCountByCategory(string $receiverId) : array
    {
        return $this->query()
                    ->where('receiver_id', $receiverId)
                    ->where('status', 'unread')
                    ->selectRaw('category_id, COUNT(*) as count')
                    ->groupBy('category_id')
                    ->get()
                    ->pluck('count', 'category_id')
                    ->toArray();
    }

    /**
     * 自定义查询方法：获取高优先级未读消息
     */
    public function getHighPriorityUnread(string $receiverId, int $limit = 10) : Collection
    {
        return $this->query()
                    ->where('receiver_id', $receiverId)
                    ->where('status', 'unread')
                    ->whereIn('priority', ['high', 'urgent'])
                    ->orderByRaw("
                CASE priority 
                    WHEN 'urgent' THEN 4
                    WHEN 'high' THEN 3
                    ELSE 0
                END DESC
            ")
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * 自定义查询方法：获取即将过期的消息
     */
    public function getExpiringMessages(int $hours = 24) : Collection
    {
        return $this->query()
                    ->whereNotNull('expires_at')
                    ->where('expires_at', '>', now())
                    ->where('expires_at', '<=', now()->addHours($hours))
                    ->where('status', 'unread')
                    ->orderBy('expires_at')
                    ->get();
    }

    /**
     * 自定义查询方法：统计消息数据
     */
    public function getStatistics(string $receiverId) : array
    {
        $query = $this->query()->where('receiver_id', $receiverId);

        return [
            'total'    => $query->count(),
            'unread'   => $query->where('status', 'unread')->count(),
            'read'     => $query->where('status', 'read')->count(),
            'archived' => $query->where('status', 'archived')->count(),
            'urgent'   => $query->where('is_urgent', true)->count(),
            'expired'  => $query->whereNotNull('expires_at')
                                ->where('expires_at', '<', now())
                                ->count(),
        ];
    }

    /**
     * 自定义查询方法：按业务线分组统计
     */
    public function getStatisticsByBiz() : array
    {
        return $this->query()
                    ->selectRaw('biz, COUNT(*) as total, 
                        SUM(CASE WHEN status = "unread" THEN 1 ELSE 0 END) as unread,
                        SUM(CASE WHEN status = "read" THEN 1 ELSE 0 END) as read,
                        SUM(CASE WHEN is_urgent = 1 THEN 1 ELSE 0 END) as urgent')
                    ->groupBy('biz')
                    ->get()
                    ->keyBy('biz')
                    ->toArray();
    }

    // 其他缺失的接口方法简化实现
    public function getPushStatistics() : array
    {
        return [];
    }

    public function searchMessages(string $keyword) : Collection
    {
        return collect();
    }

    public function getRecentMessages(int $limit = 10) : Collection
    {
        return collect();
    }

    public function getPopularMessages() : Collection
    {
        return collect();
    }

    public function getTrendData() : array
    {
        return [];
    }

    public function getUserBehaviorStats() : array
    {
        return [];
    }

    public function getCategoryStats() : array
    {
        return [];
    }

    public function getTemplateUsageStats() : array
    {
        return [];
    }

    public function getChannelEffectivenessStats() : array
    {
        return [];
    }

    public function getSendVolumeStats() : array
    {
        return [];
    }

    public function getReadRateStats() : array
    {
        return [];
    }

    public function findSimilarMessages(int $messageId) : Collection
    {
        return collect();
    }

    public function getExpiredMessages() : Collection
    {
        return collect();
    }

    public function getLongUnreadMessages(int $days = 30) : Collection
    {
        return collect();
    }

    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = '-created_at';
}
