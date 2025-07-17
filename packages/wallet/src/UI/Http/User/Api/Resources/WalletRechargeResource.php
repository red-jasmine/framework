<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

class WalletRechargeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'remark' => $this->remark,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // 扩展信息
            'status_label' => $this->status?->label(),
            'status_color' => $this->status?->color(),
            'payment_method_label' => $this->payment_method?->label(),
            
            // 关联数据
            'wallet' => $this->whenLoaded('wallet', function () {
                return [
                    'id' => $this->wallet->id,
                    'type' => $this->wallet->type,
                    'type_label' => $this->wallet->type?->label(),
                ];
            }),
        ];
    }
} 