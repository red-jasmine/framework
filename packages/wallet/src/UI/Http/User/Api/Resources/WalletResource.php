<?php

namespace RedJasmine\Wallet\UI\Http\User\Api\Resources;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Wallet\Domain\Models\Wallet;

/**
 * @mixin Wallet
 */
class WalletResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => (string) $this->id,
            'owner_type' => $this->owner_type,
            'owner_id'   => $this->owner_id,
            'type'       => $this->type,
            'currency'   => $this->currency,
            'balance'    => $this->balance,
            'freeze'     => $this->freeze,
            'status'     => $this->status,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ];
    }
} 