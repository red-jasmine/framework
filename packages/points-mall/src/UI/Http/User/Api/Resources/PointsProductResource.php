<?php

namespace RedJasmine\PointsMall\UI\Http\User\Api\Resources;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin PointsProduct
 */
class PointsProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'image'           => $this->image,
            'point'           => $this->point,
            'price_currency'  => $this->price_currency,
            'price_amount'    => $this->price_amount,
            'payment_mode'    => $this->payment_mode,
            'stock'           => $this->stock,
            'exchange_limit'  => $this->exchange_limit,
            'category_id'     => $this->category_id,
            'product_type'    => $this->product_type,
            'product_id'      => $this->product_id,
            'created_at'      => $this->created_at,

            // 关联资源
            'category'        => $this->whenLoaded('category', function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            // 扩展信息
            'available_stock' => $this->getAvailableStock(),
            'is_on_sale'      => $this->isOnSale(),
            'is_sold_out'     => $this->isSoldOut(),
        ];
    }
}