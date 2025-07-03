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
            'id'                  => (string) $this->id,
            'owner_id'            => $this->owner_id,
            'owner_type'          => $this->owner_type,
            'title'               => $this->title,
            'slogan'              => $this->slogan,
            'product_type'        => $this->product_type,
            'delivery_methods'    => $this->delivery_methods,
            'status'              => $this->status,
            'is_multiple_spec'    => $this->is_multiple_spec,
            'image'               => $this->image,
            'barcode'             => $this->barcode,
            'sort'                => $this->sort,
            'unit_quantity'       => $this->unit_quantity,
            'unit'                => $this->unit,
            'freight_payer'       => $this->freight_payer,
            'freight_template_id' => $this->freight_template_id,
            'price'               => $this->price->formatByDecimal(),
            'market_price'        => $this->market_price?->formatByDecimal(),
            'sub_stock'           => $this->sub_stock, // 库存扣减方式
            'tax_rate'            => $this->tax_rate, // 库存扣减方式
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
            'on_sale_time'        => $this->on_sale_time?->format('Y-m-d H:i:s'),
            'sold_out_time'       => $this->sold_out_time?->format('Y-m-d H:i:s'),
            'stop_sale_time'      => $this->stop_sale_time?->format('Y-m-d H:i:s'),
            'product_model'       => $this->product_model,
            'brand_id'            => $this->brand_id,
            'category_id'         => $this->category_id,
            $this->mergeWhen($this->relationLoaded('extension'),
                $this->relationLoaded('extension') ? new ProductExtensionResource($this->whenLoaded('extension')) : null),
            'brand'               => new BrandResource($this->whenLoaded('brand')),
            //'category'            => new CategoryResource($this->whenLoaded('category')),
            //'productGroup'        => new GroupResource($this->whenLoaded('productGroup')),
            'skus'                => ProductSkuResource::collection($this->whenLoaded('skus')),
            'services'            => ServiceResource::collection($this->whenLoaded('services')),
            'tags'                => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
