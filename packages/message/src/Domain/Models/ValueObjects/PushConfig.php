<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\ValueObjects;

use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 推送配置值对象
 */
class PushConfig extends ValueObject
{
    /**
     * 推送渠道列表
     * @var PushChannelEnum[]
     */
    public readonly array $channels;

    /**
     * 推送参数
     */
    public readonly array $parameters;

    /**
     * 重试配置
     */
    public readonly array $retryConfig;

    /**
     * 是否立即推送
     */
    public readonly bool $immediate;

    /**
     * 延迟推送时间（秒）
     */
    public readonly ?int $delaySeconds;

    public function __construct(
        array $channels = [],
        array $parameters = [],
        array $retryConfig = [],
        bool $immediate = true,
        ?int $delaySeconds = null
    ) {
        $this->channels = $this->validateChannels($channels);
        $this->parameters = $parameters;
        $this->retryConfig = array_merge([
            'max_attempts' => 3,
            'delay' => 60, // 秒
            'backoff_multiplier' => 2,
        ], $retryConfig);
        $this->immediate = $immediate;
        $this->delaySeconds = $delaySeconds;

        $this->validate();
    }

    /**
     * 验证推送渠道
     */
    private function validateChannels(array $channels): array
    {
        $validChannels = [];
        
        foreach ($channels as $channel) {
            if (is_string($channel)) {
                $channel = PushChannelEnum::from($channel);
            }
            
            if (!$channel instanceof PushChannelEnum) {
                throw new \InvalidArgumentException('无效的推送渠道');
            }
            
            $validChannels[] = $channel;
        }

        return $validChannels;
    }

    /**
     * 验证配置
     */
    protected function validate(): void
    {
        if ($this->delaySeconds !== null && $this->delaySeconds < 0) {
            throw new \InvalidArgumentException('延迟时间不能为负数');
        }

        if ($this->retryConfig['max_attempts'] < 0) {
            throw new \InvalidArgumentException('最大重试次数不能为负数');
        }

        if ($this->retryConfig['delay'] < 0) {
            throw new \InvalidArgumentException('重试延迟时间不能为负数');
        }
    }

    /**
     * 是否包含指定渠道
     */
    public function hasChannel(PushChannelEnum $channel): bool
    {
        return in_array($channel, $this->channels, true);
    }

    /**
     * 获取渠道数量
     */
    public function getChannelCount(): int
    {
        return count($this->channels);
    }

    /**
     * 是否启用了推送
     */
    public function isPushEnabled(): bool
    {
        return !empty($this->channels);
    }

    /**
     * 获取实时推送渠道
     */
    public function getRealtimeChannels(): array
    {
        return array_filter($this->channels, fn(PushChannelEnum $channel) => $channel->isRealtime());
    }

    /**
     * 获取非实时推送渠道
     */
    public function getNonRealtimeChannels(): array
    {
        return array_filter($this->channels, fn(PushChannelEnum $channel) => !$channel->isRealtime());
    }

    /**
     * 获取指定渠道的参数
     */
    public function getChannelParameters(PushChannelEnum $channel): array
    {
        return $this->parameters[$channel->value] ?? [];
    }

    /**
     * 计算重试延迟时间
     */
    public function calculateRetryDelay(int $attempt): int
    {
        $baseDelay = $this->retryConfig['delay'];
        $multiplier = $this->retryConfig['backoff_multiplier'];
        
        return (int) ($baseDelay * pow($multiplier, $attempt - 1));
    }
}
