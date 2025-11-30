<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Illuminate\Support\Collection;
use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Field;
use RedJasmine\Ecommerce\Domain\Form\Data\Form;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesServices;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * 产品数据类
 * 该类用于表示产品的各种属性和信息
 */
class Product extends Data
{

    // 产品所属用户
    public UserInterface $owner;


    public string $market = 'default';
    // 产品类型
    public ProductTypeEnum $productType;

    /**
     * 支持的履约方式
     * @var array
     */
    public array $shippingTypes = [];

    /**
     * 运费模板
     * @var FreightTemplateData[]
     */
    public array $freightTemplates = [];

    // 产品标题
    public string $title;

    // 产品副标题（可选）
    public ?string $slogan;

    // URL友好标识
    public ?string $slug = null;

    public ?string $spu;

    // 产品状态，默认为"在售"
    public ProductStatusEnum $status = ProductStatusEnum::AVAILABLE;

    // 是否单独下单
    public bool $isAloneOrder = false;
    // 是否预售
    public bool $isPreSale = false;

    // 是否全新
    public bool $isBrandNew = false;


    // 产品图片（可选）
    public ?string $image = null;


    // 是否定制化
    public bool $isCustomized = false;
    // 发货时间
    public int $deliveryTime = 0;
    // 排序（可选）
    public int $sort = 0;
    // SPU ID（可选）
    public ?int $spuId = null;
    // 类别ID（可选）
    public ?int $categoryId = null;
    // 品牌ID（可选）
    public ?int $brandId = null;
    // 产品型号（可选）
    public ?string $modelCode = null;
    /**
     * 产品分组ID
     * @var ?int
     */
    public ?int $productGroupId = null;

    public array $extendProductGroups = [];
    public array $tags                = [];
    public array $services            = [];

    // 最小购买限制（可选）
    public ?int $minLimit = 0;
    // 最大购买限制（可选）
    public ?int $maxLimit = 0;
    // 步进限制
    public int $stepLimit = 1;
    // VIP等级
    public int $vip = 0;

    // 积分
    public int $giftPoint = 0;

    /**
     * 提示
     * @var string|null
     */
    public ?string $tips = null;

    /**
     * 关键字
     * @var string|null
     */
    public ?string $metaTitle = null;
    /**
     * 关键字
     * @var string|null
     */
    public ?string $metaKeywords = null;
    /**
     * 描述
     * @var string|null
     */
    public ?string $metaDescription = null;


    /**
     * 媒体
     * 产品级别的媒体
     * @var ProductMedia[]
     */
    public array $media = [];


    // 备注（可选）
    public ?string $remarks;
    /**
     * 定制工具
     * @var array|null
     */
    public ?array $tools;
    /**
     * 扩展属性
     * @var array|null
     */
    public ?array $extra;


    /**
     * 售后服务
     * @var AfterSalesService[]
     */
    public array $afterSalesServices = [];

//    /**
//     * 承诺服务
//     * @var PromiseServices|null
//     */
//    public ?PromiseServices $promiseServices;

    /**
     * 基础属性
     * @var Collection<Attribute>|null
     */
    public ?Collection $basicAttrs = null;

    /**
     * 销售属性
     * @var Collection<Attribute>|null
     */
    public ?Collection $saleAttrs = null;

    /**
     * 自定义属性
     * @var Collection<Attribute>|null
     */
    public ?Collection $customizeAttrs = null;


    // 有SKU多变体
    public bool $hasVariants = false;
    /**
     * SKU变体集合
     * @var Collection<Variant>|null
     */
    public ?Collection $variants = null;

    /**
     * @var Form|null
     */
    public ?Form $form = null;


    /**
     * 多语言
     * @var ProductTranslation[]
     */
    public array $translations = [];

    /**
     * 商品价格货币
     * @var Currency
     */
    #[WithCast(CurrencyCast::class)]
    public Currency $currency;

    // 商品中 没有价格 ， 所有的价格都在 SKU变体中设置

    /**
     * 税率
     * @var float
     */
    public float $taxRate = 0;


    public function __construct()
    {

        $this->afterSalesServices = static::defaultAfterSalesServices();
    }


    public static function defaultAfterSalesServices() : array
    {
        $services = [];
        foreach (RefundTypeEnum::baseTypes() as $type) {
            $services[] = AfterSalesService::from(['refundType' => $type]);
        }
        return $services;
    }


}
