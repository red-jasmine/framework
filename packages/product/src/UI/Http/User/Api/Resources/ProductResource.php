<?php

namespace RedJasmine\Product\UI\Http\User\Api\Resources;

use Illuminate\Http\Request;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request) : array
    {

        return [
            'id'                  => (string) $this->id,
            'owner_id'            => $this->owner_id,
            'owner_type'          => $this->owner_type,
            'title'               => $this->title,
            'slogan'              => $this->slogan,
            'product_type'        => $this->product_type,
            'shipping_types'    => $this->shipping_types,
            'status'              => $this->status,
            'has_variants'        => $this->has_variants,
            'image'               => $this->image,
            'barcode'             => $this->barcode,
            'sort'                => $this->sort,
            'freight_payer'       => $this->freight_payer,
            'freight_template_id' => $this->freight_template_id,
            'price'               => $this->price->formatByDecimal(),
            'market_price'        => $this->market_price?->formatByDecimal(),
            'tax_rate'            => $this->tax_rate,
            'stock'               => $this->stock,
            'channel_stock'       => $this->channel_stock,
            'delivery_time'       => $this->delivery_time,
            'vip'                 => $this->vip,
            'gift_point'          => $this->gift_point,
            'min_limit'           => $this->min_limit,
            'max_limit'           => $this->max_limit,
            'step_limit'          => $this->step_limit,
            'is_hot'              => $this->is_hot,
            'is_new'              => $this->is_new,
            'is_best'             => $this->is_best,
            'is_benefit'          => $this->is_benefit,
            'views'               => $this->views,
            'sales'               => $this->sales,
            'version'             => $this->version,
            'available_at'        => $this->available_at?->format('Y-m-d H:i:s'),
            'paused_at'           => $this->paused_at?->format('Y-m-d H:i:s'),
            'unavailable_at'      => $this->unavailable_at?->format('Y-m-d H:i:s'),
            'model_code'     => $this->model_code,
            'brand_id'       => $this->brand_id,
            'category_id'    => $this->category_id,
            $this->mergeWhen($this->relationLoaded('extension'),
                $this->relationLoaded('extension') ? new ProductExtensionResource($this->whenLoaded('extension')) : null),
            'brand'          => new BrandResource($this->whenLoaded('brand')),
            //'category'            => new CategoryResource($this->whenLoaded('category')),
            //'productGroup'        => new GroupResource($this->whenLoaded('productGroup')),
            'variants'       => ProductVariantResource::collection($this->whenLoaded('variants')),
            'services'       => ServiceResource::collection($this->whenLoaded('services')),
            'tags'           => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
