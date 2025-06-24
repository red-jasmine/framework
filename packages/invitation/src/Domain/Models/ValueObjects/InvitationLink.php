<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\ValueObjects;

use RedJasmine\Invitation\Domain\Models\Enums\PlatformType;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 邀请链接值对象
 */
final class InvitationLink extends ValueObject
{
    public function __construct(
        public readonly string $url,                      // 链接地址
        public readonly PlatformType $platformType,       // 平台类型
        public readonly array $parameters = []            // 参数列表
    ) {
        $this->validate();
    }

    /**
     * 验证数据有效性
     */
    protected function validate(): void
    {
        if (empty($this->url)) {
            throw new \InvalidArgumentException('链接地址不能为空');
        }

        if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('链接地址格式不正确');
        }

        if (strlen($this->url) > 2048) {
            throw new \InvalidArgumentException('链接地址长度不能超过2048个字符');
        }

        // 验证必须包含邀请码参数
        $urlParts = parse_url($this->url);
        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParams);
            if (!isset($queryParams['invitation_code']) && !isset($queryParams['ic'])) {
                throw new \InvalidArgumentException('邀请链接必须包含邀请码参数');
            }
        }
    }

    /**
     * 构建完整链接
     */
    public function build(): string
    {
        if (empty($this->parameters)) {
            return $this->url;
        }

        $urlParts = parse_url($this->url);
        $query = $urlParts['query'] ?? '';
        
        parse_str($query, $queryParams);
        $queryParams = array_merge($queryParams, $this->parameters);
        
        $newQuery = http_build_query($queryParams);
        
        return $urlParts['scheme'] . '://' . $urlParts['host'] 
            . ($urlParts['port'] ?? '') 
            . ($urlParts['path'] ?? '/') 
            . ($newQuery ? '?' . $newQuery : '') 
            . ($urlParts['fragment'] ? '#' . $urlParts['fragment'] : '');
    }

    /**
     * 解析链接参数
     */
    public function parseParameters(): array
    {
        $urlParts = parse_url($this->url);
        if (!isset($urlParts['query'])) {
            return [];
        }

        parse_str($urlParts['query'], $queryParams);
        return $queryParams;
    }

    /**
     * 获取邀请码
     */
    public function getInvitationCode(): ?string
    {
        $params = $this->parseParameters();
        return $params['invitation_code'] ?? $params['ic'] ?? null;
    }

    /**
     * 添加参数
     */
    public function withParameters(array $parameters): self
    {
        return new self(
            url: $this->url,
            platformType: $this->platformType,
            parameters: array_merge($this->parameters, $parameters)
        );
    }

    /**
     * 添加单个参数
     */
    public function withParameter(string $name, string $value): self
    {
        return $this->withParameters([$name => $value]);
    }

    /**
     * 转为数组
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'platform_type' => $this->platformType->value,
            'parameters' => $this->parameters,
        ];
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'] ?? '',
            platformType: PlatformType::from($data['platform_type'] ?? 'web'),
            parameters: $data['parameters'] ?? []
        );
    }

    /**
     * 检查是否相等
     */
    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->url === $other->url && 
               $this->platformType === $other->platformType &&
               $this->parameters === $other->parameters;
    }

    /**
     * 转为字符串
     */
    public function toString(): string
    {
        return $this->build();
    }
} 