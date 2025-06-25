<?php

namespace RedJasmine\Invitation\Exceptions;

use RedJasmine\Support\Exceptions\AbstractException;

/**
 * 邀请领域异常
 */
class InvitationException extends AbstractException
{
    // 邀请码相关错误码
    public const CODE_NOT_FOUND = 10001;
    public const CODE_INVALID = 10002;
    public const CODE_EXPIRED = 10003;
    public const CODE_EXHAUSTED = 10004;
    public const CODE_DISABLED = 10005;
    public const CODE_ALREADY_EXISTS = 10006;
    public const CODE_GENERATION_FAILED = 10007;

    // 邀请记录相关错误码
    public const RECORD_NOT_FOUND = 20001;
    public const RECORD_ALREADY_COMPLETED = 20002;
    public const RECORD_INVALID = 20003;

    // 权限相关错误码
    public const PERMISSION_DENIED = 30001;
    public const RATE_LIMIT_EXCEEDED = 30002;
    public const IP_LIMIT_EXCEEDED = 30003;
    public const DEVICE_LIMIT_EXCEEDED = 30004;

    // 链接相关错误码
    public const LINK_INVALID = 40001;  
    public const LINK_EXPIRED = 40002;
    public const LINK_SIGNATURE_INVALID = 40003;
    public const TARGET_URL_INVALID = 40004;

    /**
     * 邀请码不存在
     */
    public static function codeNotFound(string $code = ''): static
    {
        return new static("邀请码不存在: {$code}", static::CODE_NOT_FOUND);
    }

    /**
     * 邀请码无效
     */
    public static function codeInvalid(string $reason = ''): static
    {
        return new static("邀请码无效: {$reason}", static::CODE_INVALID);
    }

    /**
     * 邀请码已过期
     */
    public static function codeExpired(): static
    {
        return new static('邀请码已过期', static::CODE_EXPIRED);
    }

    /**
     * 邀请码已用尽
     */
    public static function codeExhausted(): static
    {
        return new static('邀请码使用次数已用尽', static::CODE_EXHAUSTED);
    }

    /**
     * 邀请码已禁用
     */
    public static function codeDisabled(): static
    {
        return new static('邀请码已被禁用', static::CODE_DISABLED);
    }

    /**
     * 邀请码已存在
     */
    public static function codeAlreadyExists(string $code): static
    {
        return new static("邀请码已存在: {$code}", static::CODE_ALREADY_EXISTS);
    }

    /**
     * 邀请码生成失败
     */
    public static function codeGenerationFailed(): static
    {
        return new static('邀请码生成失败，请重试', static::CODE_GENERATION_FAILED);
    }

    /**
     * 邀请记录不存在
     */
    public static function recordNotFound(): static
    {
        return new static('邀请记录不存在', static::RECORD_NOT_FOUND);
    }

    /**
     * 邀请记录已完成
     */
    public static function recordAlreadyCompleted(): static
    {
        return new static('邀请记录已完成', static::RECORD_ALREADY_COMPLETED);
    }

    /**
     * 访问被拒绝
     */
    public static function permissionDenied(string $action = ''): static
    {
        return new static("没有权限执行操作: {$action}", static::PERMISSION_DENIED);
    }

    /**
     * 超过使用频率限制
     */
    public static function rateLimitExceeded(): static
    {
        return new static('使用频率过高，请稍后重试', static::RATE_LIMIT_EXCEEDED);
    }

    /**
     * 超过IP使用限制
     */
    public static function ipLimitExceeded(): static
    {
        return new static('该IP今日使用次数已达上限', static::IP_LIMIT_EXCEEDED);
    }

    /**
     * 超过设备使用限制
     */
    public static function deviceLimitExceeded(): static
    {
        return new static('该设备今日使用次数已达上限', static::DEVICE_LIMIT_EXCEEDED);
    }

    /**
     * 邀请链接无效
     */
    public static function linkInvalid(): static
    {
        return new static('邀请链接无效', static::LINK_INVALID);
    }

    /**
     * 邀请链接已过期
     */
    public static function linkExpired(): static
    {
        return new static('邀请链接已过期', static::LINK_EXPIRED);
    }

    /**
     * 链接签名无效
     */
    public static function linkSignatureInvalid(): static
    {
        return new static('邀请链接签名验证失败', static::LINK_SIGNATURE_INVALID);
    }

    /**
     * 目标URL无效
     */
    public static function targetUrlInvalid(string $url = ''): static
    {
        return new static("目标URL无效: {$url}", static::TARGET_URL_INVALID);
    }
} 