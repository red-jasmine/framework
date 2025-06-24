<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 邀请统计实体
 */
class InvitationStatistics extends Model
{
    protected $table = 'invitation_statistics';

    protected $fillable = [
        'invitation_code_id',
        'stat_date',
        'visit_count',
        'unique_visitor_count',
        'register_count',
        'order_count',
        'order_amount',
        'share_count',
        'conversion_rate',
    ];

    protected $casts = [
        'stat_date' => 'date',
        'visit_count' => 'integer',
        'unique_visitor_count' => 'integer',
        'register_count' => 'integer',
        'order_count' => 'integer',
        'order_amount' => 'decimal:2',
        'share_count' => 'integer',
        'conversion_rate' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 所属邀请码
     */
    public function invitationCode(): BelongsTo
    {
        return $this->belongsTo(InvitationCode::class);
    }

    /**
     * 计算转化率
     */
    public function calculateConversionRate(): void
    {
        if ($this->visit_count > 0) {
            $this->conversion_rate = round(
                ($this->register_count / $this->visit_count) * 100,
                4
            );
        } else {
            $this->conversion_rate = 0;
        }
    }

    /**
     * 更新统计数据
     */
    public function updateStats(
        int $visitCount = 0,
        int $uniqueVisitorCount = 0,
        int $registerCount = 0,
        int $orderCount = 0,
        float $orderAmount = 0.0,
        int $shareCount = 0
    ): void {
        $this->visit_count += $visitCount;
        $this->unique_visitor_count += $uniqueVisitorCount;
        $this->register_count += $registerCount;
        $this->order_count += $orderCount;
        $this->order_amount += $orderAmount;
        $this->share_count += $shareCount;

        $this->calculateConversionRate();
    }

    /**
     * 获取统计摘要
     */
    public function getSummary(): array
    {
        return [
            'visit_count' => $this->visit_count,
            'unique_visitor_count' => $this->unique_visitor_count,
            'register_count' => $this->register_count,
            'order_count' => $this->order_count,
            'order_amount' => $this->order_amount,
            'share_count' => $this->share_count,
            'conversion_rate' => $this->conversion_rate,
        ];
    }

    /**
     * 获取或创建统计记录
     */
    public static function getOrCreate(int $invitationCodeId, \DateTime $date): self
    {
        return self::firstOrCreate([
            'invitation_code_id' => $invitationCodeId,
            'stat_date' => $date->format('Y-m-d'),
        ]);
    }
} 