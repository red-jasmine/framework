<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Infrastructure\Services;

/**
 * 邀请码生成器
 */
final class InvitationCodeGenerator
{
    /**
     * 生成邀请码
     */
    public function generate(): string
    {
        $length = config('invitation.code.default_length', 8);
        $charset = config('invitation.code.charset', '23456789ABCDEFGHJKLMNPQRSTUVWXYZ');
        $retryTimes = config('invitation.code.generate_retry_times', 3);

        for ($i = 0; $i < $retryTimes; $i++) {
            $code = $this->generateRandomCode($length, $charset);
            
            // 检查是否已存在（这里简化处理，实际应该注入仓库检查）
            if ($this->isValidCode($code)) {
                return $code;
            }
        }

        throw new \RuntimeException('邀请码生成失败，请稍后重试');
    }

    /**
     * 生成随机邀请码
     */
    protected function generateRandomCode(int $length, string $charset): string
    {
        $code = '';
        $charsetLength = strlen($charset);
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $charset[random_int(0, $charsetLength - 1)];
        }

        return $code;
    }

    /**
     * 验证邀请码是否有效
     */
    protected function isValidCode(string $code): bool
    {
        // 检查敏感词
        $forbiddenWords = config('invitation.code.forbidden_words', []);
        foreach ($forbiddenWords as $word) {
            if (stripos($code, $word) !== false) {
                return false;
            }
        }

        return true;
    }
} 