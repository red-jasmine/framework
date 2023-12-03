<?php

namespace RedJasmine\Product\Services\Product\Validators;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use RedJasmine\Product\Enums\Product\FreightPayerEnum;
use RedJasmine\Product\Enums\Product\ProductStatus;
use RedJasmine\Product\Enums\Product\ProductTypeEnum;
use RedJasmine\Product\Enums\Product\ShippingTypeEnum;
use RedJasmine\Product\Enums\Product\SubStockTypeEnum;
use RedJasmine\Product\Services\Product\ProductService;
use RedJasmine\Product\Services\Product\Validators\Rules\BrandRule;
use RedJasmine\Product\Services\Product\Validators\Rules\CategoryRule;
use RedJasmine\Product\Services\Product\Validators\Rules\PriceRule;
use RedJasmine\Product\Services\Product\Validators\Rules\SellerCategoryRule;
use RedJasmine\Support\Enums\BoolIntEnum;
use RedJasmine\Support\Rules\NotZeroExistsRule;

class BasicValidator extends AbstractProductValidator
{
    public function withValidator(Validator $validator) : void
    {

        $validator->after(function (Validator $validator) {
            $data = $validator->getData();
            if ((int)($data['is_multiple_spec'] ?? 0)) {
                $validator->setValue('is_sku', 0);
                $validator->setValue('spu_id', 0);

            } else {
                $validator->setValue('is_sku', 1);
                $validator->setValue('spu_id', 0);
                $validator->setValue('skus', []);
                $validator->setValue('info.sale_props', []);
            }

        });


    }


    public function attributes() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('attribute'))->toArray();

    }

    public function fields() : array
    {
        $fields = [
            'category_id'        => [ 'attribute' => '类目', 'rules' => [ 'sometimes', new CategoryRule() ], ],
            'brand_id'           => [ 'attribute' => '品牌', 'rules' => [ 'sometimes', new BrandRule() ], ],
            'seller_category_id' => [ 'attribute' => '卖家分类', 'rules' => [ 'sometimes', new SellerCategoryRule() ], ],
            'product_type'       => [ 'attribute' => '商品类型', 'rules' => [ new Enum(ProductTypeEnum::class) ], ],
            'shipping_type'      => [ 'attribute' => '发货方式', 'rules' => [ new Enum(ShippingTypeEnum::class) ], ],
            'title'              => [ 'attribute' => '标题', 'rules' => [ 'required', 'max:60', 'min:2' ], ],
            'image'              => [ 'attribute' => '主图', 'rules' => [ 'sometimes', 'max:255' ], ],
            'barcode'            => [ 'attribute' => '条形码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'outer_id'           => [ 'attribute' => '商品编码', 'rules' => [ 'sometimes', 'max:64' ], ],
            'stock'              => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer', 'min:0', 'max:' . ProductService::MAX_QUANTITY ], ],
            'status'             => [ 'attribute' => '状态', 'rules' => [ 'required', new Enum(ProductStatus::class) ], ],
            'price'              => [ 'attribute' => '价格', 'rules' => [ 'required', new PriceRule() ], ],
            'market_price'       => [ 'attribute' => '市场价', 'rules' => [ 'sometimes', new PriceRule(true) ], ],
            'cost_price'         => [ 'attribute' => '成本价', 'rules' => [ 'sometimes', new PriceRule(true) ], ],
            'is_multiple_spec'   => [ 'attribute' => '多规格', 'rules' => [ 'required', new Enum(BoolIntEnum::class) ], ],
            'min'                => [ 'attribute' => '起购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'max'                => [ 'attribute' => '限购量', 'rules' => [ 'sometimes', 'integer' ], ],
            'multiple'           => [ 'attribute' => '倍数', 'rules' => [ 'sometimes', 'integer' ], ],
            'freight_payer'      => [ 'attribute' => '运费承担方', 'rules' => [ 'required', new Enum(FreightPayerEnum::class) ], ],
            'sub_stock'          => [ 'attribute' => '减库存方式', 'rules' => [ 'required', new Enum(SubStockTypeEnum::class) ], ],
            'stock'              => [ 'attribute' => '库存', 'rules' => [ 'required', 'integer', 'min:0' ], ],
            'fake_sales'         => [ 'attribute' => '虚拟销量', 'rules' => [ 'sometimes', 'integer', 'min:0' ], ],
            'postage_id'         => [ 'attribute' => '运费模板', 'rules' => [ 'sometimes' ], ],
            'delivery_time'      => [ 'attribute' => '发货时间', 'rules' => [ 'required', 'integer' ], ],
            'vip'                => [ 'attribute' => 'VIP', 'rules' => [ 'sometimes', 'integer' ], ],
            'points'             => [ 'attribute' => '积分', 'rules' => [ 'sometimes', 'integer' ], ],
            'keywords'           => [ 'attribute' => '关键字', 'rules' => [ 'sometimes', 'max:100' ], ],


            'is_hot'     => [ 'attribute' => '热销', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_new'     => [ 'attribute' => '新品', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_best'    => [ 'attribute' => '精品', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],
            'is_benefit' => [ 'attribute' => '特惠', 'rules' => [ 'sometimes', new Enum(BoolIntEnum::class) ], ],

            'info.desc'        => [ 'attribute' => '描述', 'rules' => [ 'sometimes', 'max:500' ], ],
            'info.web_detail'  => [ 'attribute' => '电脑详情', 'rules' => [], ],
            'info.wap_detail'  => [ 'attribute' => '手机详情', 'rules' => [], ],
            'info.images'      => [ 'attribute' => '图片集', 'rules' => [], ],
            'info.videos'      => [ 'attribute' => '视频集', 'rules' => [], ],
            'info.weight'      => [ 'attribute' => '重量', 'rules' => [], ],
            'info.width'       => [ 'attribute' => '宽度', 'rules' => [], ],
            'info.height'      => [ 'attribute' => '高度', 'rules' => [], ],
            'info.length'      => [ 'attribute' => '长度', 'rules' => [], ],
            'info.size'        => [ 'attribute' => '大小', 'rules' => [], ],
            'info.extends'     => [ 'attribute' => '扩展', 'rules' => [ 'sometimes', 'array' ], ],
            'info.tools'       => [ 'attribute' => '工具', 'rules' => [ 'sometimes', 'tools' ], ],
            'info.remarks'     => [ 'attribute' => '备注', 'rules' => [], ],
            'info.basic_props' => [ 'attribute' => '基础属性', 'rules' => [ 'sometimes', ], ],
            'info.sale_props'  => [ 'attribute' => '销售属性', 'rules' => [ 'sometimes', ], ],
        ];

        $sku = [
            //'skus.*.product_type'  => $fields['product_type'],
            //'skus.*.shipping_type' => $fields['shipping_type'],
            //'skus.*.title'         => $fields['title'],
            'skus.*.price'        => $fields['price'],
            'skus.*.market_price' => $fields['market_price'],
            'skus.*.cost_price'   => $fields['cost_price'],
            'skus.*.min'          => $fields['min'],
            'skus.*.max'          => $fields['max'],
            'skus.*.multiple'     => $fields['multiple'],
            'skus.*.image'        => $fields['image'],
            'skus.*.barcode'      => $fields['barcode'],
            'skus.*.outer_id'     => $fields['outer_id'],
            'skus.*.stock'        => $fields['stock'],
            'skus.*.properties'   => [ 'attribute' => '规格', 'rules' => [ 'sometimes', ], ],
        ];


        return array_merge($fields, $sku);
    }

    public function rules() : array
    {
        return collect($this->fields())->keys()->combine(collect($this->fields())->pluck('rules'))->toArray();
    }


}
