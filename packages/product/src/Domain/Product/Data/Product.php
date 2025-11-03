<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Cknow\Money\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Field;
use RedJasmine\Ecommerce\Domain\Form\Data\Form;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderQuantityLimitTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesService;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\AfterSalesServices;
use RedJasmine\Product\Domain\Product\Models\Enums\FreightPayerEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Product\Models\Enums\SubStockTypeEnum;
use RedJasmine\Product\Domain\Product\Models\ValueObjects\Medium;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

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
     * 配送方式
     * 当实物时选择配送方式
     * @var array
     */
    public array $deliveryMethods = [];

    // 产品标题
    public string $title;
    // 产品副标题（可选）
    public ?string $slogan;


    public ?string $spu;

    // 产品状态，默认为“在售”
    public ProductStatusEnum $status = ProductStatusEnum::AVAILABLE;
    // 运费支付者，默认为“卖家”
    public FreightPayerEnum $freightPayer = FreightPayerEnum::SELLER;
    // 库存子类型，默认为“默认”
    public SubStockTypeEnum $subStock = SubStockTypeEnum::DEFAULT;

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
    public ?int $sort = 0;
    // SPU ID（可选）
    public ?int $spuId = null;
    // 类别ID（可选）
    public int $categoryId = 0;
    // 品牌ID（可选）
    public int $brandId = 0;
    // 产品型号（可选）
    public ?string $modelCode = null;
    /**
     * 产品分组ID
     * @var int
     */
    public int $productGroupId = 0;

    public array $extendProductGroups = [];
    public array $tags                = [];
    public array $services            = [];
    // 邮费模板ID（可选）
    public ?int $freightTemplateId = null;
    // 最小购买限制（可选）
    public ?int $minLimit = 0;
    // 最大购买限制（可选）
    public ?int $maxLimit = 0;
    // 步进限制
    public int $stepLimit = 1;
    // VIP等级
    public int $vip = 0;

    // 订单数量限制类型
    public OrderQuantityLimitTypeEnum $orderQuantityLimitType = OrderQuantityLimitTypeEnum::UNLIMITED;

    // 订单数量限制数量（可选）
    public ?int $orderQuantityLimitNum = null;
    // 积分
    public int $giftPoint = 0;
    // 是否热门
    public bool $isHot = false;
    // 是否新品
    public bool $isNew = false;
    // 是否最佳
    public bool $isBest = false;
    // 是否优惠
    public bool $isBenefit = false;

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


    // 如果 启用了多规格那么就使用规格中的数据
    public int $stock = 0;

    public int $safetyStock = 0;
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
