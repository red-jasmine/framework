<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Invitation\Domain\Models\Enums\DestinationType;
use RedJasmine\Invitation\Domain\Models\Enums\PlatformType;

/**
 * 邀请去向实体
 */
class InvitationDestination extends Model
{
    protected $table = 'invitation_destinations';

    protected $fillable = [
        'invitation_code_id',
        'destination_type',
        'destination_id',
        'destination_url',
        'platform_type',
        'platform_config',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'destination_type' => DestinationType::class,
        'platform_type' => PlatformType::class,
        'platform_config' => 'array',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'is_default' => false,
        'sort_order' => 0,
    ];

    /**
     * 所属邀请码
     */
    public function invitationCode(): BelongsTo
    {
        return $this->belongsTo(InvitationCode::class);
    }

    /**
     * 生成链接
     */
    public function generateUrl(array $parameters = []): string
    {
        $baseUrl = $this->destination_url;
        
        if (empty($baseUrl)) {
            $baseUrl = $this->destination_type->defaultPath();
        }

        // 添加平台域名
        $domain = config("invitation.link.domains.{$this->platform_type->value}");
        if ($domain) {
            $baseUrl = rtrim($domain, '/') . '/' . ltrim($baseUrl, '/');
        }

        // 添加参数
        if (!empty($parameters)) {
            $query = http_build_query($parameters);
            $baseUrl .= (strpos($baseUrl, '?') === false ? '?' : '&') . $query;
        }

        return $baseUrl;
    }

    /**
     * 是否支持该平台
     */
    public function isSupported(PlatformType $platform): bool
    {
        return $this->platform_type === $platform;
    }

    /**
     * 设置为默认去向
     */
    public function setAsDefault(): void
    {
        // 取消同一邀请码下的其他默认去向
        self::where('invitation_code_id', $this->invitation_code_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->is_default = true;
    }
} 