<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 转账场景
 */
enum TransferSceneEnum: string
{
    use EnumsHelper;

    case OTHER = 'other';
    case TRANSFER = 'transfer';
    case MARKETING = 'marketing';
    case COMMISSION = 'commission';

    case ADMINISTRATIVE = 'administrative';
    case CLAIMS = 'claims';
    case REMUNERATION = 'remuneration';
    case REIMBURSEMENT = 'reimbursement';
    case SUBSIDY = 'subsidy';
    case SERVICE = 'service';
    case PROCUREMENT = 'procurement';

    public static function labels() : array
    {
        return [
            self::OTHER->value          => '其他',
            self::TRANSFER->value       => '转账',
            self::MARKETING->value      => '营销',
            self::COMMISSION->value     => '佣金',
            self::ADMINISTRATIVE->value => '行政',
            self::CLAIMS->value         => '理赔',
            self::REMUNERATION->value   => '补发',
            self::REIMBURSEMENT->value  => '报销',
            self::SUBSIDY->value        => '补贴',
            self::SERVICE->value        => '服务',
            self::PROCUREMENT->value    => '采购',
        ];
    }
}