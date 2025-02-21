<?php

namespace RedJasmine\Product\UI\Http\Seller\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'                    => $this->id,
            'owner_id'              => $this->owner_id,
            'owner_type'            => $this->owner_type,
            'title'                 => $this->title,
            'slogan'                => $this->slogan,
            'product_type'          => $this->product_type,
            'shipping_type'         => $this->shipping_type,
            'status'                => $this->status,
            'is_multiple_spec'      => $this->is_multiple_spec,
            'image'                 => $this->image,
            'barcode'               => $this->barcode,
            'outer_id'              => $this->outer_id,
            'supplier_type'         => $this->supplier_type,
            'supplier_id'           => $this->supplier_id,
            'supplier_product_id'   => $this->supplier_product_id,
            'sort'                  => $this->sort,
            'unit_quantity'         => $this->unit_quantity,
            'unit'                  => $this->unit,
            'freight_payer'         => $this->freight_payer,
            'postage_id'            => $this->postage_id,
            'price'                 => (string)$this->price,
            'market_price'          => $this->market_price?->value(),
            'cost_price'            => $this->cost_price?->value(),
            'sub_stock'             => $this->sub_stock,
            'stock'                 => $this->stock,
            'channel_stock'         => $this->channel_stock,
            'lock_stock'            => $this->lock_stock,
            'delivery_time'         => $this->delivery_time,
            'vip'                   => $this->vip,
            'gift_point'                => $this->gift_point,
            'min_limit'             => $this->min_limit,
            'max_limit'             => $this->max_limit,
            'step_limit'            => $this->step_limit,
            'is_hot'                => $this->is_hot,
            'is_new'                => $this->is_new,
            'is_best'               => $this->is_best,
            'is_benefit'            => $this->is_benefit,
            'safety_stock'          => $this->safety_stock,
            'views'                 => $this->views,
            'sales'                 => $this->sales,
            'version'               => $this->version,
            'on_sale_time'          => $this->on_sale_time?->format('Y-m-d H:i:s'),
            'sold_out_time'         => $this->sold_out_time?->format('Y-m-d H:i:s'),
            'stop_sale_time'        => $this->stop_sale_time?->format('Y-m-d H:i:s'),
            'modified_time'         => $this->modified_time,
            'creator_id'            => $this->creator_id,
            'creator_type'          => $this->creator_type,
            'updater_id'            => $this->updater_id,
            'updater_type'          => $this->updater_type,
            'created_at'            => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'            => $this->updated_at?->format('Y-m-d H:i:s'),
            'brand_id'              => $this->brand_id,
            'product_model'         => $this->product_model,
            'category_id'           => $this->category_id,
            'product_group_id'      => $this->product_group_id,
            'brand'                 => new BrandResource($this->whenLoaded('brand')),
            'category'              => new CategoryResource($this->whenLoaded('category')),
            'product_group'         => new GroupResource($this->whenLoaded('productGroup')),
            'extend_product_groups' => GroupResource::collection($this->whenLoaded('extendProductGroups')),
            'tags'                  => ProductTagResource::collection($this->whenLoaded('tags')),
            $this->mergeWhen($this->relationLoaded('extension'),
                             $this->relationLoaded('extension') ? new ProductExtensionResource($this->whenLoaded('extension')) : null),
            'skus'                  => ProductSkuResource::collection($this->whenLoaded('skus')),
        ];
    }
}
