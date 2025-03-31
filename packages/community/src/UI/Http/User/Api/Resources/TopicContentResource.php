<?php

namespace RedJasmine\Community\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Community\Domain\Models\Extensions\TopicContent;

/** @mixin TopicContent */
class TopicContentResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [

            'content' => $this->content,
        ];
    }
}
