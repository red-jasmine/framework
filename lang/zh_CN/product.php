<?php
return [
    'labels' => [
        'product'    => '商品',
        'basic_info' => '基本信息',
        'product_attributes' => '商品属性',
        'sale_info' => '销售信息',
        'description' => '商品描述',
        'operate' => '运营',
        'seo' => 'SEO',
        'shipping' => '发货服务',
        'supplier' => '供应商',
        'other' => '其他',
    ],
    'fields' => [
        'id'                  => '商品ID',
        'sku_id'              => '规格ID',
        'owner_type'          => '所属者类型',
        'owner_id'            => '所属者ID',
        'product_type'        => '商品类型',
        'shipping_type'       => '发货类型',
        'title'               => '标题',
        'slogan'              => '卖点',
        'image'               => '主图',
        'barcode'             => '条形码',
        'outer_id'            => '商家编码',
        'product_model'       => '型号',
        'keywords'            => '关键字',
        'is_multiple_spec'    => '多规格',
        'is_customized'       => '是的定制',
        'sort'                => '排序',
        'status'              => '状态',
        'price'               => '销售价',
        'market_price'        => '市场价',
        'cost_price'          => '成本价',
        'brand_id'            => '品牌',
        'category_id'         => '类目',
        'seller_category_id'  => '卖家分类',
        'properties'          => '规格属性值',
        'properties_name'     => '规格属性名称',
        'freight_payer'       => '运费承担方',
        'postage_id'          => '运费模板ID',
        'unit'                => '单位',
        'unit_quantity'       => '单位数量',
        'min_limit'           => '起购量',
        'max_limit'           => '限购量',
        'step_limit'          => '购买倍数',
        'sub_stock'           => '减库存方式',
        'stock'               => '库存',
        'lock_stock'          => '锁定库存',
        'safety_stock'        => '安全库存',
        'sales'               => '销量',
        'views'               => '浏览量',
        'fake_sales'          => '虚假销量',
        'delivery_time'       => '发货时间',
        'vip'                 => 'VIP',
        'points'              => '积分',
        'is_hot'              => '热销',
        'is_new'              => '新品',
        'is_best'             => '精品',
        'is_benefit'          => '特惠',
        'tips'                => '提示',
        'supplier_type'       => '供应商类型',
        'supplier_id'         => '供应商ID',
        'supplier_product_id' => '供应商商品ID',
        'supplier_sku_id'     => '供应商规格ID',
        'on_sale_time'        => '上架时间',
        'sold_out_time'       => '售停时间',
        'off_sale_time'       => '下架时间',
        'modified_time'       => '修改时间',
        'creator_type'        => '创建者类型',
        'creator_id'          => '创建者UID',
        'updater_type'        => '更新者类型',
        'updater_id'          => '更新者UID',
        'description'         => '描述',
        'detail'              => '详情',
        'videos'              => '视频集',
        'images'              => '图片集',
        'weight'              => '重',
        'width'               => '宽',
        'height'              => '高',
        'length'              => '长',
        'size'                => '体积',
        'remarks'             => '备注',
        'basic_props'         => '基础属性',
        'sale_props'          => '销售属性',
        'tools'               => '工具',
        'extends'             => '扩展',
        'skus'                => '规格',
        'created_at'          => '创建时间',
        'version'             => '版本',
    ],

    'props'   => [
        'pid'    => '属性名称',
        'vid'    => '属性值',
        'values' => '属性值',
        'alias'  => '别名',
    ],


    'enums'=>[
        'status'=>[
            'on_sale'     => '上架',
            'draft'       => '草稿',
            'pre_sale'    => '上架',
            'sold_out'    => '停售',
            'off_shelf'   => '下架',
            'forbid_sale' => '禁售',
            'deleted'     => '上架',
        ],
    ],
    'options' => [
    ],
];
