<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class WalletWithdrawalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'amount' => $this->amount,
            'bank_card_id' => $this->bank_card_id,
            'remark' => $this->remark,
            'status' => $this->status,
            'processed_at' => $this->processed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 扩展信息
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            
            // 关联数据
            'wallet' => $this->whenLoaded('wallet', function () {
                return [
                    'id' => $this->wallet->id,
                    'type' => $this->wallet->type,
                    'type_label' => $this->wallet->type?->label(),
                ];
            }),
            
            'bank_card' => $this->whenLoaded('bank_card', function () {
                return [
                    'id' => $this->bank_card->id,
                    'card_number' => $this->bank_card->masked_card_number,
                    'bank_name' => $this->bank_card->bank_name,
                ];
            }),
        ];
    }
} 