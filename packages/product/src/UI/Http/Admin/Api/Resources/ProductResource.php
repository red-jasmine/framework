<?php

namespace RedJasmine\Product\UI\Http\Admin\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'                  => $this->id,
            'owner_id'            => $this->owner_id,
            'owner_type'          => $this->owner_type,
            'title'               => $this->title,
            'slogan'              => $this->slogan,
            'slug'                => $this->slug,
            'product_type'        => $this->product_type,
            'shipping_type'       => $this->shipping_type,
            'status'              => $this->status,
            'has_variants'        => $this->has_variants,
            'image'               => $this->image,
            'barcode'             => $this->barcode,
            'sort'                => $this->sort,
            'freight_payer'       => $this->freight_payer,
            'freight_template_id' => $this->freight_template_id,
            'price'               => (string) $this->price,
            'market_price'        => (string) $this->market_price,
            'cost_price'          => (string) $this->cost_price,
            'stock'               => $this->stock,
            'channel_stock'       => $this->channel_stock,
            'lock_stock'          => $this->lock_stock,

            'delivery_time'    => $this->delivery_time,
            'vip'              => $this->vip,
            'gift_point'       => $this->gift_point,
            'min_limit'        => $this->min_limit,
            'max_limit'        => $this->max_limit,
            'step_limit'       => $this->step_limit,
            'is_hot'           => $this->is_hot,
            'is_new'           => $this->is_new,
            'is_best'          => $this->is_best,
            'is_benefit'       => $this->is_benefit,
            'safety_stock'     => $this->safety_stock,
            'views'            => $this->views,
            'sales'            => $this->sales,
            'version'          => $this->version,
            'available_at'     => $this->available_at?->format('Y-m-d H:i:s'),
            'paused_at'        => $this->paused_at?->format('Y-m-d H:i:s'),
            'unavailable_at'   => $this->unavailable_at?->format('Y-m-d H:i:s'),
            'modified_at'      => $this->modified_at,
            'creator_id'       => $this->creator_id,
            'creator_type'     => $this->creator_type,
            'updater_id'       => $this->updater_id,
            'updater_type'     => $this->updater_type,
            'created_at'       => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'       => $this->updated_at?->format('Y-m-d H:i:s'),
            'brand_id'         => $this->brand_id,
            'model_code'       => $this->model_code,
            'category_id'      => $this->category_id,
            'product_group_id' => $this->product_group_id,
            $this->mergeWhen($this->relationLoaded('extension'),
                $this->relationLoaded('extension') ? new ProductExtensionResource($this->whenLoaded('extension')) : null),
            'brand'            => new BrandResource($this->whenLoaded('brand')),
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'productGroup'     => new GroupResource($this->whenLoaded('productGroup')),
            'variants'         => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
