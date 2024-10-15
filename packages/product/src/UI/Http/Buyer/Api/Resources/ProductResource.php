<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'                 => $this->id,
            'owner_id'           => $this->owner_id,
            'owner_type'         => $this->owner_type,
            'title'              => $this->title,
            'slogan'             => $this->slogan,
            'product_type'       => $this->product_type,
            'shipping_type'      => $this->shipping_type,
            'status'             => $this->status,
            'is_multiple_spec'   => $this->is_multiple_spec,
            'image'              => $this->image,
            'barcode'            => $this->barcode,
            'sort'               => $this->sort,
            'unit_quantity'      => $this->unit_quantity,
            'unit'               => $this->unit,
            'freight_payer'      => $this->freight_payer,
            'postage_id'         => $this->postage_id,
            'price'              => (string)$this->price,
            'market_price'       => (string)$this->market_price,
            'sub_stock'          => $this->sub_stock,
            'stock'              => $this->stock,
            'channel_stock'      => $this->channel_stock,
            'delivery_time'      => $this->delivery_time,
            'vip'                => $this->vip,
            'points'             => $this->points,
            'min_limit'          => $this->min_limit,
            'max_limit'          => $this->max_limit,
            'step_limit'         => $this->step_limit,
            'is_hot'             => $this->is_hot,
            'is_new'             => $this->is_new,
            'is_best'            => $this->is_best,
            'is_benefit'         => $this->is_benefit,
            'views'              => $this->views,
            'sales'              => $this->sales,
            'version'            => $this->version,
            'on_sale_time'       => $this->on_sale_time?->format('Y-m-d H:i:s'),
            'sold_out_time'      => $this->sold_out_time?->format('Y-m-d H:i:s'),
            'off_sale_time'      => $this->off_sale_time?->format('Y-m-d H:i:s'),
            'brand_id'           => $this->brand_id,
            'product_model'      => $this->product_model,
            'category_id'        => $this->category_id,
            'seller_category_id' => $this->seller_category_id,
            $this->mergeWhen($this->relationLoaded('info'),
                             $this->relationLoaded('info') ? new ProductInfoResource($this->whenLoaded('info')) : null),
            'brand'              => new BrandResource($this->whenLoaded('brand')),
            'category'           => new CategoryResource($this->whenLoaded('category')),
            'sellerCategory'     => new GroupResource($this->whenLoaded('sellerCategory')),
            'skus'               => ProductSkuResource::collection($this->whenLoaded('skus')),
        ];
    }
}
