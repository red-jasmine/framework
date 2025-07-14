<?php

namespace RedJasmine\Order\Domain\Data;


use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Helpers\HasSerialNumber;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class OrderProductData extends Data
{

    use HasSerialNumber;

    /**
     * 商品类型
     * @var ProductTypeEnum
     */
    #[WithCast(EnumCast::class, type: ProductTypeEnum::class)]
    public ProductTypeEnum $orderProductType;


    /**
     * 发货类型
     * @var ShippingTypeEnum
     */
    #[WithCast(EnumCast::class, type: ShippingTypeEnum::class)]
    public ShippingTypeEnum $shippingType;

    public string $title;

    public ?string $skuName;
    /**
     * 商品多态类型
     * @var string
     */
    public string $productType;
    public int    $productId;
    public int    $skuId = 0;

    /**
     * 商品件数
     * @var int
     */
    public int $quantity;

    // 单位数量
    public int $unitQuantity = 1;
    // 单位（可选）
    public ?string $unit = null;

    /**
     * @var Money
     */
    public Money $price;
    /**
     * 成本
     * @var Money|null
     */
    public ?Money $costPrice = null;
    /**
     * 商品优惠
     * @var Money|null
     */
    public ?Money $discountAmount = null;
    /**
     * 服务费
     * @var Money|null
     */
    public ?Money $serviceAmount = null;
    /**
     * 税费
     * @var Money|null
     */
    public ?Money $taxAmount = null;

    public string|int|float $texRate = 0;

    public int     $brandId             = 0;
    public int     $categoryId          = 0;
    public int     $productGroupId      = 0;
    public ?string $image               = null;
    public ?string $outerProductId      = null;
    public ?string $outerSkuId          = null;
    public ?string $barcode             = null;
    public ?string $sellerCustomStatus  = null;
    public ?string $outerOrderProductId = null;
    /**
     * 赠送积分
     * @var int
     */
    public int $giftPoint = 0;
    /**
     * 售后服务
     * @var AfterSalesService[]
     */
    public array $afterSalesServices = [];

    public ?string $sellerRemarks = null;
    public ?string $sellerMessage = null;
    public ?string $buyerRemarks  = null;
    public ?string $buyerMessage  = null;
    public ?array  $buyerExtra    = null;
    public ?array  $sellerExtra   = null;
    public ?array  $otherExtra    = null;
    public ?array  $tools         = null;
    public ?array  $form          = null;
    public ?string $contact       = null;
    public ?string $password      = null;
    public ?array  $customized    = null; // 定制

}
