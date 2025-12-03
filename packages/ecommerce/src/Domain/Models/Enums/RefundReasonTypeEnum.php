<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 售后理由类型（大类）
 *
 * 参考大型电商平台（淘宝、京东、拼多多等）的标准做法，抽象出的标准理由类型分类
 * 每个理由类型下可以配置多个具体的理由条目，支持不同业态和商品类型
 */
enum RefundReasonTypeEnum: string
{
    use EnumsHelper;

    /**
     * 商品质量问题
     * 包括：商品瑕疵、损坏、功能故障、性能不达标等
     */
    case QUALITY_ISSUE = 'quality_issue';

    /**
     * 商品与描述不符
     * 包括：尺寸不符、颜色不符、规格不符、材质不符、功能不符等
     */
    case DESCRIPTION_MISMATCH = 'description_mismatch';

    /**
     * 物流问题
     * 包括：未收到货、物流延迟、包裹丢失、配送错误、包装破损等
     */
    case LOGISTICS_ISSUE = 'logistics_issue';

    /**
     * 服务问题
     * 包括：服务态度差、发货慢、客服问题、售后不及时、安装调试不到位等
     */
    case SERVICE_ISSUE = 'service_issue';

    /**
     * 个人原因
     * 包括：不想要了、多买了、买错了、不需要了、价格变动等
     */
    case PERSONAL_REASON = 'personal_reason';

    /**
     * 其他原因
     * 包括：促销活动未兑现、发票凭证问题、其他未列明原因等
     */
    case OTHER = 'other';

    public static function labels(): array
    {
        return [
            self::QUALITY_ISSUE->value => '商品质量问题',
            self::DESCRIPTION_MISMATCH->value => '商品与描述不符',
            self::LOGISTICS_ISSUE->value => '物流问题',
            self::SERVICE_ISSUE->value => '服务问题',
            self::PERSONAL_REASON->value => '个人原因',
            self::OTHER->value => '其他原因',
        ];
    }

    public static function colors(): array
    {
        return [
            self::QUALITY_ISSUE->value => 'danger',
            self::DESCRIPTION_MISMATCH->value => 'warning',
            self::LOGISTICS_ISSUE->value => 'warning',
            self::SERVICE_ISSUE->value => 'warning',
            self::PERSONAL_REASON->value => 'info',
            self::OTHER->value => 'gray',
        ];
    }

    public static function icons(): array
    {
        return [
            self::QUALITY_ISSUE->value => 'heroicon-o-exclamation-triangle',
            self::DESCRIPTION_MISMATCH->value => 'heroicon-o-document-text',
            self::LOGISTICS_ISSUE->value => 'heroicon-o-truck',
            self::SERVICE_ISSUE->value => 'heroicon-o-chat-bubble-left-right',
            self::PERSONAL_REASON->value => 'heroicon-o-user',
            self::OTHER->value => 'heroicon-o-ellipsis-horizontal',
        ];
    }

    /**
     * 获取理由类型的描述
     */
    public function description(): string
    {
        return match ($this) {
            self::QUALITY_ISSUE => '商品存在质量问题，包括瑕疵、损坏、功能故障等',
            self::DESCRIPTION_MISMATCH => '商品与描述不符，包括尺寸、颜色、规格、材质、功能等不符',
            self::LOGISTICS_ISSUE => '物流相关问题，包括未收到货、物流延迟、包裹丢失等',
            self::SERVICE_ISSUE => '服务相关问题，包括服务态度、发货速度、客服响应等',
            self::PERSONAL_REASON => '个人原因，包括不想要了、多买了、买错了等',
            self::OTHER => '其他未列明的原因',
        };
    }

    /**
     * 判断是否为商家责任类型
     * 商家责任类型通常需要商家承担运费等额外成本
     */
    public function isMerchantResponsibility(): bool
    {
        return match ($this) {
            self::QUALITY_ISSUE,
            self::DESCRIPTION_MISMATCH,
            self::LOGISTICS_ISSUE,
            self::SERVICE_ISSUE => true,
            self::PERSONAL_REASON,
            self::OTHER => false,
        };
    }
}
