<?php

namespace RedJasmine\Order\Domain\Data;


use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class OrderProductData extends Data
{
    /**
     * 商品类型
     * @var ProductTypeEnum
     */
    public ProductTypeEnum $orderProductType;
    /**
     * 发货类型
     * @var ShippingTypeEnum
     */
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
    public int $num;

    // 单位数量
    public int $unitQuantity = 1;
    // 单位（可选）
    public ?string $unit = null;

    public Amount  $price;
    public Amount  $costPrice;
    public Amount  $taxAmount;
    public Amount  $discountAmount;
    public int     $categoryId          = 0;
    public int     $productGroupId      = 0;
    public ?string $image               = null;
    public ?string $outerId             = null;
    public ?string $outerSkuId          = null;
    public ?string $barcode             = null;
    public ?string $sellerCustomStatus  = null;
    public ?string $outerOrderProductId = null;
    /**
     * 赠送积分
     * @var int
     */
    public int $points = 0;
    /**
     * 售后服务
     * @var AfterSalesService[]
     */
    public array $afterSalesServices = [];

    public ?string $sellerRemarks = null;
    public ?string $sellerMessage = null;
    public ?string $buyerRemarks  = null;
    public ?string $buyerMessage  = null;
    public ?array  $buyerExpands  = null;
    public ?array  $sellerExpands = null;
    public ?array  $otherExpands  = null;
    public ?array  $tools         = null;
    public ?array  $form          = null;

    public function __construct()
    {
        $this->taxAmount      = new Amount(0);
        $this->discountAmount = new Amount(0);
        $this->costPrice      = new Amount(0);

    }
}
