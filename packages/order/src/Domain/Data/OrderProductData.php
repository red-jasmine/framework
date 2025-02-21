<?php

namespace RedJasmine\Order\Domain\Data;


use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class OrderProductData extends Data
{
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

    public Money   $price;
    public Money   $costPrice;
    public Money   $taxAmount;
    public Money   $discountAmount;
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
    public ?array  $buyerExtras   = null;
    public ?array  $sellerExtras  = null;
    public ?array  $otherExtras   = null;
    public ?array  $tools         = null;
    public ?array  $form          = null;

    public function __construct()
    {
        $this->taxAmount      = new Money(0);
        $this->discountAmount = new Money(0);
        $this->costPrice      = new Money(0);

    }
}
