<?php

namespace RedJasmine\Shopping\UI\Http\Buyer\Api\Resources;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Support\UI\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductInfo
 */
class ProductInfoResource extends JsonResource
{
    public function toArray($request) : array
    {

        return [
            'is_available'      => $this->isAvailable,
            'product_type'      => $this->productType,
            'shipping_types'    => $this->shippingTypes,
            'stock_info'        => $this->stockInfo,
            'title'             => $this->title,
            'attrs_name'   => $this->propertiesName,
            'image'             => $this->image,
            'min_limit'         => $this->minLimit,
            'step_limit'        => $this->stepLimit,
            'max_limit'         => $this->maxLimit,
            'market_price'      => $this->getProductAmountInfo()->marketPrice,
            'price'             => $this->getProductAmountInfo()->price,
            'total_price'       => $this->getProductAmountInfo()->totalPrice,
            'discount_amount'   => $this->getProductAmountInfo()->discountAmount,
            'product_amount'    => $this->getProductAmountInfo()->productAmount,
            'tax_rate'          => $this->getProductAmountInfo()->taxRate,
            'tax_amount'          => $this->getProductAmountInfo()->taxAmount,
            'service_amount'    => $this->getProductAmountInfo()->serviceAmount,
            'available_coupons' => $this->getProductAmountInfo()->availableCoupons,
            'coupons'           => $this->getProductAmountInfo()->coupons,
        ];

    }
}