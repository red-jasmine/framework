<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Wallet\Domain\Models\WalletTransaction;

/**
 * @mixin WalletTransaction
 */
class WalletTransactionResource extends JsonResource
{
    public function toArray($request) : array
    {
        return [
            //'id'               => $this->id,
            'no'   => $this->no,
            'wallet_id'        => (string) $this->wallet_id,
            'type'             => $this->direction,
            'currency'         => $this->currency,
            'amount'           => $this->amount,
            'balance_before'   => $this->balance_before,
            'balance_after'    => $this->balance_after,
            'freeze_before'    => $this->freeze_before,
            'freeze_after'     => $this->freeze_after,
            'description'      => $this->description,
            'remarks'          => $this->remarks,
            'status'           => $this->status,
            'trade_time'       => $this->trade_time,
            'transaction_type' => $this->transaction_type,
            'title'            => $this->title,
            'description'      => $this->description,
            'bill_type'        => $this->bill_type,
            'bill_type'        => $this->bill_type,
            'out_trade_no'     => $this->out_trade_no,
            'tags'             => $this->tags,
            'app_id'           => $this->app_id,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
} 