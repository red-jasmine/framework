<?php

namespace RedJasmine\User\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;

use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\User\Domain\Models\User;

/** @mixin User */
class UserBaseResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'mobile'    => $this->mobile, // TODO 掩码
            'email'     => $this->email, // TODO 掩码
            'nickname'  => $this->nickname,
            'avatar'    => $this->avatar,
            'gender'    => $this->gender,
            'birthday'  => $this->birthday,
            'type'      => $this->type,
            'status'    => $this->status,
            'biography' => $this->biography,
        ];
    }
}
