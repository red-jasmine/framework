<?php

namespace RedJasmine\Product\UI\Http\Buyer\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin \RedJasmine\Product\Domain\Product\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'                  => $this->id,
            'owner_id'            => $this->owner_id,
            'owner_type'          => $this->owner_type,
            'title'               => $this->title,
            'product_type'        => $this->product_type,
            'shipping_type'       => $this->shipping_type,
            'status'              => $this->status,
            'is_multiple_spec'    => $this->is_multiple_spec,
            'image'               => $this->image,
            'barcode'             => $this->barcode,
            'outer_id'            => $this->outer_id,
            'supplier_type'       => $this->supplier_type,
            'supplier_id'         => $this->supplier_id,
            'supplier_product_id' => $this->supplier_product_id,
            'sort'                => $this->sort,
            'unit_name'           => $this->unit_name,
            'unit'                => $this->unit,
            'freight_payer'       => $this->freight_payer,
            'postage_id'          => $this->postage_id,
            'price'               => (string)$this->price,
            'market_price'        => (string)$this->market_price,
            'cost_price'          => (string)$this->cost_price,
            'sub_stock'           => $this->sub_stock,
            'stock'               => $this->stock,
            'channel_stock'       => $this->channel_stock,
            'lock_stock'          => $this->lock_stock,
            'promise_services'    => $this->promise_services,
            'delivery_time'       => $this->delivery_time,
            'vip'                 => $this->vip,
            'points'              => $this->points,
            'min_limit'           => $this->min_limit,
            'max_limit'           => $this->max_limit,
            'step_limit'          => $this->step_limit,
            'is_hot'              => $this->is_hot,
            'is_new'              => $this->is_new,
            'is_best'             => $this->is_best,
            'is_benefit'          => $this->is_benefit,
            'safety_stock'        => $this->safety_stock,
            'views'               => $this->views,
            'sales'               => $this->sales,
            'version'             => $this->version,
            'on_sale_time'        => $this->on_sale_time?->format('Y-m-d H:i:s'),
            'sold_out_time'       => $this->sold_out_time?->format('Y-m-d H:i:s'),
            'off_sale_time'       => $this->off_sale_time?->format('Y-m-d H:i:s'),
            'modified_time'       => $this->modified_time,
            'creator_id'          => $this->creator_id,
            'creator_type'        => $this->creator_type,
            'updater_id'          => $this->updater_id,
            'updater_type'        => $this->updater_type,
            'created_at'          => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'          => $this->updated_at?->format('Y-m-d H:i:s'),
            'brand_id'            => $this->brand_id,
            'category_id'         => $this->category_id,
            'seller_category_id'  => $this->seller_category_id,
            $this->mergeWhen($this->relationLoaded('info'), $this->relationLoaded('info') ? new ProductInfoResource($this->whenLoaded('info')) : null),
            'brand'               => new BrandResource($this->whenLoaded('brand')),
            'category'            => new CategoryResource($this->whenLoaded('category')),
            'sellerCategory'      => new SellerCategoryResource($this->whenLoaded('sellerCategory')),
            'skus'                => ProductSkuResource::collection($this->whenLoaded('skus')),
        ];
    }
}
