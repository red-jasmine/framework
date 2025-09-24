<?php

namespace RedJasmine\Coupon\Domain\Services;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\Enums\CouponTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleObjectTypeEnum;
use RedJasmine\Coupon\Domain\Models\Enums\RuleTypeEnum;
use RedJasmine\Coupon\Domain\Models\ValueObjects\RuleItem;
use RedJasmine\Coupon\Domain\Models\ValueObjects\RuleValue;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Support\Foundation\Service\Service;

class CouponRuleService extends Service
{
    public function __construct(
        protected UserCouponRepositoryInterface $userCouponRepository,
    ) {
    }

    /**
     * @param  Coupon  $coupon
     * @param  PurchaseFactor  $purchaseFactor
     *
     * @return bool
     */
    public function checkReceiveRules(Coupon $coupon, PurchaseFactor $purchaseFactor) : bool
    {
        $rules = $coupon->receive_rules;
        // 无领取门槛
        if (count($rules) <= 0) {
            return true;
        }

        $factors = [];
        // 查询用户领取次数
        $factors[] = [
            'objectType'  => RuleObjectTypeEnum::USER_RECEIVE_LIMIT,
            'objectValue' => $this->userCouponRepository->getUserCouponCountByCoupon($purchaseFactor->buyer, $coupon),
        ];
        // TODO
        // 于商家的领取规则、客户分组、客户标签、客户VIP、等

        // 对于平台用户的领取规则、用户分组、用户标签、用户VIP、等
        $factors[] = [
            'objectType'  => RuleObjectTypeEnum::USER_GROUP,
            'objectValue' => '',
        ];
        return $this->meetRules($rules, $factors);
    }

    /**
     * 检查使用规则
     *
     * @param  Coupon  $coupon
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return bool
     */
    public function checkUsageRules(Coupon $coupon, ProductPurchaseFactor $productPurchaseFactor) : bool
    {

        // 获取当前规格
        $rules = $coupon->usage_rules;


        // 如果不是系统券时
        if ($coupon->coupon_type === CouponTypeEnum::SHOP) {
            $shopRules         = RuleItem::collect($coupon->getSellerUsageRules());
            $factorSellerValue = $productPurchaseFactor->getProductInfo()->product->seller->getType()
                                 .'|'.
                                 $productPurchaseFactor->getProductInfo()->product->seller->getID();


            if (!$this->meetRules($shopRules, [['objectType' => RuleObjectTypeEnum::SELLER, 'objectValue' => $factorSellerValue,]])) {
                return false;
            }
        }

        $factors = [];

        $factors[] = [
            'objectType'  => RuleObjectTypeEnum::PRODUCT,
            'objectValue' => $productPurchaseFactor->getProductInfo()->product->id,
        ];
        $factors[] = [
            'objectType'  => RuleObjectTypeEnum::BRAND,
            'objectValue' => $productPurchaseFactor->getProductInfo()->brandId,
        ];
        $factors[] = [
            'objectType'  => RuleObjectTypeEnum::CATEGORY,
            'objectValue' => $productPurchaseFactor->getProductInfo()->categoryId,
        ];


        return $this->meetRules($rules, $factors);

    }


    /**
     * 满足规则
     *
     * @param  array  $rules
     * @param  array  $factors
     *
     * @return bool
     */
    protected function meetRules(array $rules, array $factors) : bool
    {
        $ruleItems    = collect(RuleItem::collect($rules));
        $factors      = collect(RuleValue::collect($factors));
        $factorGroups = $factors->groupBy('objectType')->all();


        // 命中排除规则
        /**
         * @var RuleItem $ruleItem
         */

        if (array_any($ruleItems->where('ruleType', RuleTypeEnum::EXCLUDE)->all(),
            fn($ruleItem) => array_any($factorGroups[$ruleItem->objectType->value]?->all() ?? [],
                fn($ruleFactor) => $ruleItem->matches($ruleFactor->objectType, $ruleFactor->objectValue)))) {
            return false;
        }

        // 然后对包含规则再次分组
        $includeRules = $ruleItems->where('ruleType', RuleTypeEnum::INCLUDE)->groupBy('objectType')->all();

        // 同  objectType 下  或的关系、 不同  objectType 下 需 全部满足
        $isMeet = true;

        foreach ($includeRules as $objectType => $objectTypeRules) {
            $objectTypeMet = false;
            foreach ($objectTypeRules as $ruleItem) {
                foreach ($factorGroups[$objectType] ?? [] as $factor) {
                    if ($ruleItem->matches($factor->objectType, $factor->objectValue)) {
                        $objectTypeMet = true;
                        break; // 找到匹配项后跳出内层循环
                    }
                }

                if ($objectTypeMet) {
                    break; // 找到匹配规则后跳出当前 objectType 的处理
                }
            }

            if (!$objectTypeMet) {
                $isMeet = false;
                break; // 只要有一个 objectType 不满足条件，整体就不满足
            }
        }
        return $isMeet;
    }
}