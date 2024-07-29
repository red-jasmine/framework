<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Data;


use RedJasmine\Order\Domain\Models\Enums\OrderProductTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\ValueObjects\PromiseServices;
use RedJasmine\Support\Data\Data;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;

class OrderProductData extends Data
{
    public function __construct()
    {
        $this->taxAmount      = new Amount(0);
        $this->discountAmount = new Amount(0);
        $this->costPrice      = new Amount(0);

    }


    /**
     * 商品类型
     * @var OrderProductTypeEnum
     */
    public OrderProductTypeEnum $orderProductType;
    /**
     * 发货类型
     * @var \RedJasmine\Order\Domain\Models\Enums\ShippingTypeEnum
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
     * 商品件数
     * @var int
     */
    public int $num;
    /**
     * 一个单位量
     * @var int
     */
    public int $unit = 1;

    public Amount $price;
    public Amount $costPrice;
    public Amount $taxAmount;
    public Amount $discountAmount;

    public int     $categoryId          = 0;
    public int     $sellerCategoryId    = 0;
    public ?string $image;
    public ?string $outerId;
    public ?string $outerSkuId;
    public ?string $barcode;
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

    public ?OrderProductInfoData $info;
}
