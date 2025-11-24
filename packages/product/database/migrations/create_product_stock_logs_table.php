<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');

            // ========== 所属者信息 ==========
            $table->string('owner_type', 64)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');

            // ========== 商品信息 ==========
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('variant_id')->comment('SKU ID（变体ID，与product_stocks表保持一致）');

            // ========== 仓库信息（多仓库支持）==========
            $table->unsignedBigInteger('warehouse_id')->default(0)->comment('仓库ID（0表示总仓/默认仓库/简单模式）');

            // ========== 操作信息 ==========
            $table->string('action_type', 32)->comment(ProductStockActionTypeEnum::comments('操作类型'));
            $table->bigInteger('action_stock')->comment('操作库存数量（根据操作类型：ADD/RESET为正数，SUB为负数，LOCK/UNLOCK/RESERVE/RELEASE/DEDUCT为操作数量）');


            $table->string('business_type', 64)->nullable()->comment('业务类型（如：order-订单, transfer-调拨, inventory-盘点, return-退货等）');
            $table->string('business_no', 128)->nullable()->comment('业务单号（如订单号、调拨单号等，用于精确查询和关联，有索引支持。如果业务实体没有单号，可将ID转换为字符串存储）');

            // ========== 备注信息（可选）==========
            // 注意：业务单号应使用 business_no 字段，此字段仅用于存储自由文本的备注信息
            $table->string('business_detail', 255)->nullable()->comment('变更明细（自由文本备注，如操作原因、说明等，不用于查询，仅用于展示。业务单号请使用 business_no 字段）');



            // ========== 库存状态（操作前）==========
            $table->bigInteger('before_stock')->comment('操作前总库存');
            $table->bigInteger('before_available_stock')->comment('操作前可用库存');
            $table->bigInteger('before_locked_stock')->comment('操作前锁定库存');
            $table->bigInteger('before_reserved_stock')->comment('操作前预留库存');


            // ========== 库存状态（操作后）==========
            $table->bigInteger('after_stock')->comment('操作后总库存');
            $table->bigInteger('after_available_stock')->comment('操作后可用库存');
            $table->bigInteger('after_locked_stock')->comment('操作后锁定库存');
            $table->bigInteger('after_reserved_stock')->comment('操作后预留库存');

            // ========== 操作信息 ==========
            $table->operator();


            // ========== 索引 ==========
            // 商品和变体索引
            $table->index('product_id', 'idx_product');
            $table->index('variant_id', 'idx_variant');
            $table->index(['product_id', 'variant_id'], 'idx_product_variant');

            // 仓库索引
            $table->index('warehouse_id', 'idx_warehouse');
            $table->index(['warehouse_id', 'variant_id'], 'idx_warehouse_variant');

            // 所属者索引
            $table->index(['owner_type', 'owner_id'], 'idx_owner');

            // 操作类型索引
            $table->index('action_type', 'idx_action_type');

            // 业务关联索引（用于查询订单、调拨单等关联的库存操作）
            $table->index(['business_type', 'business_no'], 'idx_business');
            $table->index('business_no', 'idx_business_no');

            // 时间索引（用于时间范围查询和排序）
            $table->index('created_at', 'idx_created_at');
            $table->index(['variant_id', 'created_at'], 'idx_variant_created');
            $table->index(['warehouse_id', 'created_at'], 'idx_warehouse_created');

            $table->comment('商品-库存-操作记录表（记录所有库存操作，支持业务关联查询）');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_stock_logs');
    }
};
