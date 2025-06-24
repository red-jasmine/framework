<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Infrastructure\Services;

use RedJasmine\Invitation\Domain\Models\InvitationCode;

/**
 * 邀请链接生成器
 */
final class InvitationLinkGenerator
{
    /**
     * 生成邀请链接
     */
    public function generate(InvitationCode $invitationCode, string $platform = 'web', array $parameters = []): string
    {
        $domain = config("invitation.link.domains.{$platform}");
        if (!$domain) {
            throw new \InvalidArgumentException("不支持的平台类型: {$platform}");
        }

        $baseUrl = rtrim($domain, '/') . '/invitation';
        
        // 合并参数
        $params = array_merge([
            'ic' => $invitationCode->code,
            'platform' => $platform,
        ], $parameters);

        $query = http_build_query($params);
        
        return $baseUrl . '?' . $query;
    }

    /**
     * 生成二维码链接
     */
    public function generateQrCode(InvitationCode $invitationCode, string $platform = 'h5', array $parameters = []): string
    {
        $link = $this->generate($invitationCode, $platform, $parameters);
        
        // 这里可以集成二维码生成服务
        return $link;
    }
} 