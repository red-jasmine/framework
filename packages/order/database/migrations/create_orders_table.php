<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\AcceptStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\RateStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\SettlementStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\ShippingStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_').'orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('order_no', 64)->unique()->comment('订单号');
            $table->string('app_id', 64)->comment('应用ID');
            $table->string('seller_type', 32)->comment('卖家类型');
            $table->unsignedBigInteger('seller_id')->comment('卖家ID');
            $table->string('seller_nickname')->nullable()->comment('卖家昵称');

            $table->string('buyer_type', 32)->comment('买家类型');
            $table->unsignedBigInteger('buyer_id')->comment('买家类型');
            $table->string('buyer_nickname')->nullable()->comment('买家昵称');

            $table->string('title')->nullable()->comment('标题');
            $table->string('order_type', 32)->comment(OrderTypeEnum::comments('订单类型'));
            $table->string('shipping_type', 32)->comment(ShippingTypeEnum::comments('发货类型'));

            $table->string('order_status', 32)->comment(OrderStatusEnum::comments('订单状态'));
            $table->string('accept_status', 32)->nullable()->comment(AcceptStatusEnum::comments('接单状态'));
            $table->string('payment_status', 32)->nullable()->comment(PaymentStatusEnum::comments('付款状态'));
            $table->string('shipping_status', 32)->nullable()->comment(ShippingStatusEnum::comments('发货状态'));
            $table->string('rate_status', 32)->nullable()->comment(RateStatusEnum::comments('评价状态'));
            $table->string('settlement_status', 32)->nullable()->comment(SettlementStatusEnum::comments('结算状态'));
            $table->string('seller_custom_status', 32)->nullable()->comment('卖家自定义状态');
            $table->string('invoice_status', 32)->nullable()->comment('发票状态');

            $table->decimal('product_amount', 12)->default(0)->comment('商品金额');
            $table->decimal('cost_amount', 12)->default(0)->comment('成本金额');
            $table->decimal('tax_amount', 12)->default(0)->comment('税费金额');
            $table->decimal('commission_amount', 12)->default(0)->comment('佣金');

            $table->decimal('product_payable_amount', 12)->default(0)->comment('商品应付金额');
            $table->decimal('freight_amount', 12)->default(0)->comment('运费');
            $table->decimal('discount_amount', 12)->default(0)->comment('订单优惠');
            $table->decimal('payable_amount', 12)->default(0)->comment('应付金额');
            $table->decimal('payment_amount', 12)->default(0)->comment('实付金额');
            $table->decimal('refund_amount', 12)->default(0)->comment('退款金额');


            $table->timestamp('created_time')->nullable()->comment('创建时间');
            $table->timestamp('payment_time')->nullable()->comment('付款时间');
            $table->timestamp('accept_time')->nullable()->comment('接单时间');
            $table->timestamp('close_time')->nullable()->comment('关闭时间');
            $table->timestamp('shipping_time')->nullable()->comment('发货时间');
            $table->timestamp('collect_time')->nullable()->comment('揽收时间');
            $table->timestamp('dispatch_time')->nullable()->comment('派送时间');
            $table->timestamp('signed_time')->nullable()->comment('签收时间');
            $table->timestamp('confirm_time')->nullable()->comment('确认时间');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('rate_time')->nullable()->comment('评价时间');
            $table->timestamp('settlement_time')->nullable()->comment('结算时间');

            $table->string('channel_type', 32)->nullable()->comment('渠道类型');
            $table->unsignedBigInteger('channel_id')->nullable()->comment('渠道ID');
            $table->string('channel_name')->nullable()->comment('渠道名称');
            $table->string('guide_type', 32)->nullable()->comment('导购类型');
            $table->unsignedBigInteger('guide_id')->nullable()->comment('导购ID');
            $table->string('guide_name')->nullable()->comment('导购名称');
            $table->string('store_type', 32)->nullable()->comment('门店类型');
            $table->unsignedBigInteger('store_id')->nullable()->comment('门店ID');
            $table->string('store_name')->nullable()->comment('门店名称');

            $table->string('client_type', 32)->nullable()->comment('客户端类型');
            $table->string('client_version', 32)->nullable()->comment('客户端版本');
            $table->string('client_ip', 32)->nullable()->comment('IP');
            //  订单来源 如：活动、购物车、商品、其他等
            $table->string('source_type', 32)->nullable()->comment('来源类型');
            $table->string('source_id', 32)->nullable()->comment('来源ID');

            // 卡密专用
            $table->string('contact')->nullable()->comment('联系方式');
            $table->string('password')->nullable()->comment('查询密码');


            // 由此可可以控制 各类订单类型 的确认时间 如：等待拼单时间、拼团时间、酒店单确认时间等 分钟
            $table->bigInteger('payment_wait_max_time')->default(0)->comment('付款等待最大时长');
            $table->bigInteger('accept_wait_max_time')->default(0)->comment('接单等待最大时长');
            $table->bigInteger('confirm_wait_max_time')->default(0)->comment('确认等待最大时长');
            $table->bigInteger('rate_wait_max_time')->default(0)->comment('评价等待最大时长');


            $table->unsignedTinyInteger('star')->nullable()->comment('加星');
            $table->unsignedTinyInteger('urge')->nullable()->comment('催单');
            $table->timestamp('urge_time')->nullable()->comment('催单时间');
            $table->boolean('is_seller_delete')->default(false)->comment('卖家删除');
            $table->boolean('is_buyer_delete')->default(false)->comment('买家删除');
            $table->string('outer_order_id', 64)->nullable()->comment('外部订单号');
            $table->string('cancel_reason')->nullable()->comment('取消原因');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->nullableMorphs('creator'); // 创建人
            $table->nullableMorphs('updater'); // 更新人
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单表');

            $table->index(['buyer_type', 'buyer_id', 'order_status'], 'idx_buyer');
            $table->index(['seller_type', 'seller_id', 'order_status'], 'idx_seller');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_').'orders');
    }
};
