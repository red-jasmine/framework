<?php

namespace RedJasmine\User\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
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
            'mobile'    => Str::mask($this->mobile, '*', 3, 4),
            'email'     => Str::mask($this->email, '*', 0,4),
            'nickname'  => $this->nickname,
            'avatar'    => $this->avatar,
            'gender'    => $this->gender,
            'birthday'  => $this->birthday,
            'type'      => $this->type,
            'status'    => $this->status,
            'biography' => $this->biography,
            'country'   => $this->country,
            'province'  => $this->province,
            'city'      => $this->city,
            'district'  => $this->district,
        ];
    }
}
