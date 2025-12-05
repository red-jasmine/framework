<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedJasmine\Message\Domain\Models\Enums\MessageStatusEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\UserData;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息仓库实现
 *
 * 基于Repository实现，提供消息实体的读写操作能力
 */
class MessageRepository extends Repository implements MessageRepositoryInterface
{
    protected static string $modelClass = Message::class;

    /**
     * 批量标记消息为已读
     */
    public function markAsRead(array $messageIds, string $bid, UserInterface $owner) : int
    {
        return $this->query()
                    ->where('biz', $bid)
                    ->where('status', MessageStatusEnum::UNREAD)
                    ->whereIn('id', $messageIds)
                    ->onlyOwner($owner)
                    ->update([
                        'status'  => MessageStatusEnum::READ,
                        'read_at' => now(),
                    ]);
    }

    public function allMarkAsReadAll(string $bid, UserInterface $owner) : int
    {
        return $this->query()
                    ->where('biz', $bid)
                    ->where('status', MessageStatusEnum::UNREAD)
                    ->onlyOwner($owner)
                    ->update([
                        'status'  => MessageStatusEnum::READ,
                        'read_at' => now(),
                    ]);
    }

    /**
     * 获取未读消息数量
     */
    public function getUnreadCount(UserInterface $owner, string $biz) : int
    {
        $query = $this->query()
                      ->where('biz', $biz)
                      ->where('status', MessageStatusEnum::UNREAD)
                      ->where('owner_type', $owner->getType())
                      ->where('owner_id', $owner->getId());

        return $query->count();
    }

    /**
     * 获取未读消息统计
     */
    public function getUnreadStatistics(UserInterface $owner, string $biz) : array
    {
        $query = $this->query()
                      ->select(['category_id', DB::raw('count(*) as total')])
                      ->where('owner_type', $owner->getType())
                      ->where('owner_id', $owner->getId())
                      ->where('biz', $biz)
                      ->where('status', MessageStatusEnum::UNREAD)
                      ->groupBy('category_id');

        return $query->get()->pluck('total', 'category_id')->toArray();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
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
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
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
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return [
            AllowedInclude::relationship('category'),
            AllowedInclude::relationship('template'),
            AllowedInclude::relationship('pushLogs'),
            AllowedInclude::relationship('category.parent'),
        ];
    }
}
