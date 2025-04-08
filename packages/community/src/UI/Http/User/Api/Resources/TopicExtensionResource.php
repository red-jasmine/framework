<?php

namespace RedJasmine\Community\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RedJasmine\Community\Domain\Models\Extensions\TopicExtension;

/** @mixin TopicExtension */
class TopicExtensionResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'content_type' => $this->content_type,
            'content'      => $this->content,
        ];
    }
}
