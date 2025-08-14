<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Services;

use RedJasmine\Message\Domain\Data\MessageData;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageReadRepositoryInterface;

/**
 * 消息规则领域服务
 */
class MessageRuleService
{
    public function __construct(
        protected MessageReadRepositoryInterface $messageReadRepository,
    ) {
    }

    /**
     * 验证推送规则
     */
    public function validatePushRules(MessageData $messageData): void
    {
        // 验证频率限制
        $this->validateFrequencyLimit($messageData);

        // 验证推送时间限制
        $this->validatePushTimeLimit($messageData);

        // 验证推送渠道限制
        $this->validateChannelLimit($messageData);

        // 验证用户偏好设置
        $this->validateUserPreferences($messageData);
    }

    /**
     * 检查频率限制
     */
    public function checkFrequencyLimit(Message $message, PushChannelEnum $channel): void
    {
        $receiverId = $message->receiver_id;
        $biz = $message->biz->value;

        // 获取频率限制配置
        $limits = $this->getFrequencyLimits($biz, $channel);

        foreach ($limits as $limit) {
            $count = $this->getMessageCount($receiverId, $biz, $limit['period']);
            
            if ($count >= $limit['max_count']) {
                throw new \InvalidArgumentException(
                    "超过频率限制: {$limit['period']}内最多发送{$limit['max_count']}条消息"
                );
            }
        }
    }

    /**
     * 验证渠道规则
     */
    public function validateChannelRules(Message $message, PushChannelEnum $channel): void
    {
        // 检查渠道是否启用
        if (!$this->isChannelEnabled($channel, $message->biz->value)) {
            throw new \InvalidArgumentException("推送渠道 {$channel->value} 未启用");
        }

        // 检查用户是否允许该渠道推送
        if (!$this->isChannelAllowedForUser($message->receiver_id, $channel)) {
            throw new \InvalidArgumentException("用户不允许 {$channel->value} 渠道推送");
        }

        // 检查消息类型是否支持该渠道
        if (!$this->isChannelSupportedForMessageType($message->type, $channel)) {
            throw new \InvalidArgumentException(
                "消息类型 {$message->type->value} 不支持 {$channel->value} 渠道推送"
            );
        }
    }

    /**
     * 选择最优推送渠道
     */
    public function selectOptimalChannel(Message $message): PushChannelEnum
    {
        $pushConfig = $message->getPushConfig();
        $availableChannels = $pushConfig->channels;

        if (empty($availableChannels)) {
            throw new \InvalidArgumentException('没有可用的推送渠道');
        }

        // 根据消息优先级选择渠道
        if ($message->isUrgent()) {
            // 紧急消息优先选择实时推送渠道
            $realtimeChannels = $pushConfig->getRealtimeChannels();
            if (!empty($realtimeChannels)) {
                return $realtimeChannels[0];
            }
        }

        // 根据用户偏好选择渠道
        $userPreferences = $this->getUserChannelPreferences($message->receiver_id);
        foreach ($userPreferences as $preferredChannel) {
            if (in_array($preferredChannel, $availableChannels, true)) {
                return $preferredChannel;
            }
        }

        // 根据渠道成功率选择
        $channelStats = $this->getChannelSuccessRates($availableChannels);
        $bestChannel = array_key_first($channelStats);

        return PushChannelEnum::from($bestChannel);
    }

    /**
     * 验证频率限制
     */
    protected function validateFrequencyLimit(MessageData $messageData): void
    {
        $receiverId = $messageData->receiverId;
        $biz = $messageData->biz->value;

        // 获取频率限制配置
        $limits = $this->getFrequencyLimits($biz);

        foreach ($limits as $limit) {
            $count = $this->getMessageCount($receiverId, $biz, $limit['period']);
            
            if ($count >= $limit['max_count']) {
                throw new \InvalidArgumentException(
                    "超过频率限制: {$limit['period']}内最多发送{$limit['max_count']}条消息"
                );
            }
        }
    }

    /**
     * 验证推送时间限制
     */
    protected function validatePushTimeLimit(MessageData $messageData): void
    {
        // 如果是紧急消息，不受时间限制
        if ($messageData->isUrgentMessage()) {
            return;
        }

        $now = now();
        $hour = $now->hour;

        // 获取免打扰时间配置
        $quietHours = $this->getQuietHours($messageData->receiverId);
        
        if ($this->isInQuietHours($hour, $quietHours)) {
            throw new \InvalidArgumentException(
                "当前时间({$hour}:00)在用户免打扰时间内"
            );
        }
    }

    /**
     * 验证推送渠道限制
     */
    protected function validateChannelLimit(MessageData $messageData): void
    {
        $channels = $messageData->getPushChannels();

        foreach ($channels as $channel) {
            // 检查渠道是否启用
            if (!$this->isChannelEnabled($channel, $messageData->biz->value)) {
                throw new \InvalidArgumentException("推送渠道 {$channel->value} 未启用");
            }

            // 检查用户是否允许该渠道推送
            if (!$this->isChannelAllowedForUser($messageData->receiverId, $channel)) {
                throw new \InvalidArgumentException("用户不允许 {$channel->value} 渠道推送");
            }
        }
    }

    /**
     * 验证用户偏好设置
     */
    protected function validateUserPreferences(MessageData $messageData): void
    {
        $preferences = $this->getUserPreferences($messageData->receiverId);

        // 检查用户是否允许接收此类型的消息
        if (!$this->isMessageTypeAllowed($messageData->type, $preferences)) {
            throw new \InvalidArgumentException(
                "用户不允许接收 {$messageData->type->value} 类型的消息"
            );
        }

        // 检查用户是否允许接收此业务线的消息
        if (!$this->isBizAllowed($messageData->biz, $preferences)) {
            throw new \InvalidArgumentException(
                "用户不允许接收 {$messageData->biz->value} 业务线的消息"
            );
        }
    }

    /**
     * 获取频率限制配置
     */
    protected function getFrequencyLimits(string $biz, ?PushChannelEnum $channel = null): array
    {
        // 从配置文件获取频率限制
        $config = config("message.frequency_limits.{$biz}", []);
        
        if ($channel) {
            $config = $config[$channel->value] ?? $config;
        }

        return $config ?: [
            ['period' => '1 hour', 'max_count' => 10],
            ['period' => '1 day', 'max_count' => 50],
        ];
    }

    /**
     * 获取指定时间段内的消息数量
     */
    protected function getMessageCount(string $receiverId, string $biz, string $period): int
    {
        $startTime = now()->sub($this->parsePeriod($period));
        
        // 这里应该调用仓库方法获取消息数量
        // 暂时返回0，实际实现时需要查询数据库
        return 0;
    }

    /**
     * 解析时间周期
     */
    protected function parsePeriod(string $period): \DateInterval
    {
        return match ($period) {
            '1 hour' => new \DateInterval('PT1H'),
            '1 day' => new \DateInterval('P1D'),
            '1 week' => new \DateInterval('P1W'),
            '1 month' => new \DateInterval('P1M'),
            default => new \DateInterval('PT1H'),
        };
    }

    /**
     * 获取用户免打扰时间
     */
    protected function getQuietHours(string $receiverId): array
    {
        // 从用户配置或数据库获取免打扰时间
        // 默认晚上10点到早上8点
        return [22, 23, 0, 1, 2, 3, 4, 5, 6, 7];
    }

    /**
     * 检查是否在免打扰时间内
     */
    protected function isInQuietHours(int $hour, array $quietHours): bool
    {
        return in_array($hour, $quietHours, true);
    }

    /**
     * 检查渠道是否启用
     */
    protected function isChannelEnabled(PushChannelEnum $channel, string $biz): bool
    {
        $enabledChannels = config("message.enabled_channels.{$biz}", []);
        return in_array($channel->value, $enabledChannels, true);
    }

    /**
     * 检查用户是否允许该渠道推送
     */
    protected function isChannelAllowedForUser(string $receiverId, PushChannelEnum $channel): bool
    {
        // 从用户设置中获取允许的推送渠道
        $allowedChannels = $this->getUserAllowedChannels($receiverId);
        return in_array($channel->value, $allowedChannels, true);
    }

    /**
     * 检查消息类型是否支持该渠道
     */
    protected function isChannelSupportedForMessageType($messageType, PushChannelEnum $channel): bool
    {
        $supportedChannels = config("message.message_type_channels.{$messageType->value}", []);
        return in_array($channel->value, $supportedChannels, true);
    }

    /**
     * 获取用户渠道偏好
     */
    protected function getUserChannelPreferences(string $receiverId): array
    {
        // 从用户设置中获取渠道偏好顺序
        return [PushChannelEnum::IN_APP, PushChannelEnum::PUSH, PushChannelEnum::EMAIL, PushChannelEnum::SMS];
    }

    /**
     * 获取渠道成功率统计
     */
    protected function getChannelSuccessRates(array $channels): array
    {
        // 从统计数据中获取各渠道的成功率
        $stats = [];
        foreach ($channels as $channel) {
            $stats[$channel->value] = 0.95; // 默认成功率
        }
        
        // 按成功率降序排序
        arsort($stats);
        
        return $stats;
    }

    /**
     * 获取用户偏好设置
     */
    protected function getUserPreferences(string $receiverId): array
    {
        // 从用户设置表获取偏好设置
        return [
            'allowed_message_types' => ['notification', 'alert', 'reminder'],
            'allowed_biz' => ['user', 'merchant', 'admin', 'system'],
            'allowed_channels' => ['in_app', 'push', 'email', 'sms'],
        ];
    }

    /**
     * 检查消息类型是否被允许
     */
    protected function isMessageTypeAllowed($messageType, array $preferences): bool
    {
        $allowedTypes = $preferences['allowed_message_types'] ?? [];
        return in_array($messageType->value, $allowedTypes, true);
    }

    /**
     * 检查业务线是否被允许
     */
    protected function isBizAllowed($biz, array $preferences): bool
    {
        $allowedBiz = $preferences['allowed_biz'] ?? [];
        return in_array($biz->value, $allowedBiz, true);
    }

    /**
     * 获取用户允许的推送渠道
     */
    protected function getUserAllowedChannels(string $receiverId): array
    {
        $preferences = $this->getUserPreferences($receiverId);
        return $preferences['allowed_channels'] ?? [];
    }
}
