<?php

namespace RedJasmine\User\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\User\Domain\Models\User;

/** @mixin User */
class UserBaseResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'           => $this->id,
            'username'     => $this->username,
            'phone_number' => $this->phone_number, // TODO 掩码
            'email'        => $this->email, // TODO 掩码
            'nickname'     => $this->nickname,
            'gender'       => $this->gender,
            'avatar'       => $this->avatar,
            'type'         => $this->type,
            'status'       => $this->status,
        ];
    }
}
