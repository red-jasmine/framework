<?php

declare(strict_types=1);

namespace RedJasmine\Message\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * 消息推送日志资源
 */
class MessagePushLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'message_id' => $this->message_id,
            'channel' => $this->channel?->value,
            'channel_label' => $this->channel?->label(),
            'external_id' => $this->external_id,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'response_time' => $this->response_time,
            'retry_count' => $this->retry_count,
            'error_message' => $this->error_message,
            'pushed_at' => $this->pushed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // 计算属性
            'is_success' => $this->status?->value === 'sent',
            'is_failed' => $this->status?->value === 'failed',
            'is_pending' => $this->status?->value === 'pending',
            'has_error' => !empty($this->error_message),
            'can_retry' => $this->status?->value === 'failed' && $this->retry_count < 3,

            // 时间相关
            'pushed_at_human' => $this->pushed_at?->diffForHumans(),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'response_time_human' => $this->getResponseTimeHuman(),

            // 性能指标
            'performance_level' => $this->getPerformanceLevel(),
            'is_slow' => $this->response_time > 3000, // 大于3秒认为慢

            // 条件字段 - 消息信息
            'message' => $this->whenLoaded('message', function () {
                return new MessageResource($this->message);
            }),

            // 推送结果详情
            'push_result' => $this->when($this->push_result, function () {
                return [
                    'success' => $this->push_result['success'] ?? false,
                    'message_id' => $this->push_result['message_id'] ?? null,
                    'response_code' => $this->push_result['response_code'] ?? null,
                    'response_message' => $this->push_result['response_message'] ?? null,
                    'delivered_at' => $this->push_result['delivered_at'] ?? null,
                    'read_at' => $this->push_result['read_at'] ?? null,
                ];
            }),

            // 错误详情
            'error_details' => $this->when($this->error_message, function () {
                return [
                    'code' => $this->error_code ?? null,
                    'message' => $this->error_message,
                    'details' => $this->error_details ?? null,
                    'retry_after' => $this->retry_after ?? null,
                ];
            }),
        ];
    }

    /**
     * 获取响应时间人类可读格式
     */
    protected function getResponseTimeHuman(): ?string
    {
        if (!$this->response_time) {
            return null;
        }

        $ms = $this->response_time;
        
        if ($ms < 1000) {
            return $ms . 'ms';
        } elseif ($ms < 60000) {
            return round($ms / 1000, 2) . 's';
        } else {
            return round($ms / 60000, 2) . 'min';
        }
    }

    /**
     * 获取性能等级
     */
    protected function getPerformanceLevel(): string
    {
        if (!$this->response_time || $this->status?->value !== 'sent') {
            return 'unknown';
        }

        $ms = $this->response_time;
        
        if ($ms < 500) {
            return 'excellent';
        } elseif ($ms < 1000) {
            return 'good';
        } elseif ($ms < 3000) {
            return 'fair';
        } else {
            return 'poor';
        }
    }

    /**
     * 获取额外的元数据
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'can_retry' => $this->can_retry ?? false,
                'max_retries' => 3,
                'retry_attempts_left' => max(0, 3 - ($this->retry_count ?? 0)),
                'is_final_attempt' => ($this->retry_count ?? 0) >= 3,
            ],
        ];
    }
}
