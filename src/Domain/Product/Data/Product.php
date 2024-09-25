<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Illuminate\Support\Collection;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\Medium;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;


class Product extends Data
{

    public ProductTypeEnum   $productType;
    public ShippingTypeEnum  $shippingType;
    public UserInterface     $owner;
    public string            $title;
    public ?string           $slogan;
    public Amount            $price;
    public Amount            $marketPrice;
    public Amount            $costPrice;
    public ProductStatusEnum $status         = ProductStatusEnum::ON_SALE;
    public FreightPayerEnum  $freightPayer   = FreightPayerEnum::SELLER;
    public SubStockTypeEnum  $subStock       = SubStockTypeEnum::DEFAULT;
    public int               $stock          = 0;
    public ?string           $image          = null;
    public ?string           $barcode        = null;
    public ?string           $outerId        = null;
    public bool              $isMultipleSpec = false;
    public bool              $isCustomized   = false;


    public int     $unit             = 1;
    public int     $deliveryTime     = 0;
    public ?int    $sort             = 0;
    public ?int    $spuId            = null;
    public ?int    $categoryId       = null;
    public ?int    $brandId          = null;
    public ?string $productModel     = null;
    public ?int    $sellerCategoryId = null;
    public ?int    $postageId        = null;
    public ?int    $minLimit         = 0;
    public ?int    $maxLimit         = 0;
    public int     $stepLimit        = 1;
    public int     $vip              = 0;
    public int     $points           = 0;
    public bool    $isHot            = false;
    public bool    $isNew            = false;
    public bool    $isBest           = false;
    public bool    $isBenefit        = false;
    public int     $safetyStock      = 0;


    /**
     * 供应商
     * @var UserInterface|null
     */
    public ?UserInterface $supplier;
    public ?int           $supplierProductId = null;

    /**
     * 提示
     * @var string|null
     */
    public ?string $tips = null;
    /**
     * 关键字
     * @var string|null
     */
    public ?string $keywords = null;
    /**
     * 描述
     * @var string|null
     */
    public ?string $description = null;

    /**
     * 产品详情
     * @var string|null
     */
    public ?string $detail = null;


    /**
     * 基础属性
     * @var Medium[]|null
     */
    public ?array $images = null;

    /**
     * 基础属性
     * @var Medium[]|null
     */
    public ?array $videos = null;


    public ?string $weight;
    public ?string $width;
    public ?string $height;
    public ?string $length;
    public ?string $size;
    public ?string $remarks;
    /**
     * 定制工具
     * @var array|null
     */
    public ?array $tools;
    /**
     *
     * @var array|null
     */
    public ?array $expands;

    /**
     * 承诺服务
     * @var PromiseServices|null
     */
    public ?PromiseServices $promiseServices;


    /**
     * 基础属性
     * @var Collection<Property>|null
     */
    public ?Collection $basicProps = null;

    /**
     * 销售属性
     * @var Collection<Property>|null
     */
    public ?Collection $saleProps = null;

    /**
     * // TODO
     * 自定义属性
     * @var Collection|null
     */
    public ?Collection $customizeProps = null;

    /**
     * 规格集合
     * @var Collection<Sku>|null
     */
    public ?Collection $skus = null;


}
