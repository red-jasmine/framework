<?php

namespace RedJasmine\PointsMall\Domain\Services;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductIdentity;
use RedJasmine\PointsMall\Domain\Contracts\ProductServiceInterface;
use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\Support\Foundation\Service\Service;

class PointsProductService extends Service
{

    public function __construct(
        protected ProductServiceInterface $productService
    ) {
    }


    public function create(PointsProduct $pointsProduct) : void
    {
        // 创建商品
        // 调用商品服务 验证商品信息
        $this->validate($pointsProduct);

        // 验证规则
    }

    public function validate(PointsProduct $pointsProduct) : void
    {

        $this->findProductInfo($pointsProduct);

        if ($pointsProduct->payment_mode === PointsProductPaymentModeEnum::POINTS) {
            $pointsProduct->price = Money::parse(0);
        }

    }

    protected function findProductInfo(PointsProduct $pointsProduct) : void
    {
        $productIdentity         = new ProductIdentity();
        $productIdentity->seller = $pointsProduct->owner;
        $productIdentity->type   = $pointsProduct->product_type;
        $productIdentity->id     = $pointsProduct->product_id;
        $productIdentity->skuId  = $pointsProduct->product_id;

        $productInfo = $this->productService->getProductInfo($productIdentity);

        $pointsProduct->title = $productInfo->title;
        $pointsProduct->image = $productInfo->image;

    }

}