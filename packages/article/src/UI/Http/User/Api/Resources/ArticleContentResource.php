<?php

namespace RedJasmine\Article\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;
use RedJasmine\Article\Domain\Models\Extensions\ArticleContent;

/** @mixin ArticleContent */
class ArticleContentResource extends JsonResource
{
    public function toArray(Request $request) : array
    {
        return [
            'content_type' => $this->content_type,
            'content'      => $this->content,
        ];
    }
}
