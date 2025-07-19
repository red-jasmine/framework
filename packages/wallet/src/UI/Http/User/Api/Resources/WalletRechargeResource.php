<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Wallet\Domain\Models\WalletRecharge;

/**
 * @mixin WalletRecharge
 */
class WalletRechargeResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            'id'                       => $this->id,
            'wallet_type'              => $this->wallet_type,
            'recharge_no'              => $this->recharge_no,
            'wallet_id'                => $this->wallet_id,
            'owner_type'               => $this->owner_type,
            'owner_id'                 => $this->owner_id,
            'amount'                   => $this->amount,
            'status'                   => $this->status,
            'recharge_time'            => $this->recharge_time,
            'exchange_rate'            => $this->exchange_rate,
            'payment_currency'         => $this->payment_currency,
            'payment_amount'           => $this->payment_amount,
            'payment_fee'              => $this->payment_fee,
            'total_payment_amount'     => $this->total_payment_amount,
            'payment_status'           => $this->payment_status,
            'payment_time'             => $this->payment_time,
            'payment_type'             => $this->payment_type,
            'payment_id'               => $this->payment_id,
            'payment_channel_trade_no' => $this->payment_channel_trade_no,
            'payment_mode'             => $this->payment_mode,
            'created_at'               => $this->created_at,
        ];
    }
} 