<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Data;


use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
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
    public string  $title;
    public ?string $skuName;
    /**
     * 商品多态类型
     * @var string
     */
    public string $productType;
    public int    $productId;
    public int    $skuId = 0;
    /**
     * 一个单位量
     * @var int
     */
    public int $unit = 1;
    /**
     * 商品件数
     * @var int
     */
    public int    $num;
    public Amount $price;
    public Amount $costPrice;
    public Amount $taxAmount;
    public Amount $discountAmount;
    public int     $categoryId          = 0;
    public int     $sellerCategoryId    = 0;
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
    public int $giftPoint = 0;
    /**
     * @var PromiseServices|null
     */
    public ?PromiseServices $promiseServices;
    public ?string $sellerRemarks = null;
    public ?string $sellerMessage = null;
    public ?string $buyerRemarks  = null;
    public ?string $buyerMessage  = null;
    public ?array  $buyerExpands  = null;
    public ?array  $sellerExpands = null;
    public ?array  $otherExpands  = null;
    public ?array  $tools         = null;

    public function __construct()
    {
        $this->taxAmount      = new Amount(0);
        $this->discountAmount = new Amount(0);
        $this->costPrice      = new Amount(0);

    }
}
