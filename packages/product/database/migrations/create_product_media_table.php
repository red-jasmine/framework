<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Media\Models\Enums\MediaTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('product_media', function (Blueprint $table) {
            // 主键和关联字段
            $table->unsignedBigInteger('id')->primary()->comment('ID');

            // 所有者字段（支持媒体单独管理）
            $table->string('owner_type', 64)->comment('所有者类型');
            $table->string('owner_id', 64)->comment('所有者ID');

            // 商品关联字段（可选，支持媒体独立管理）
            $table->unsignedBigInteger('product_id')->nullable()->comment('商品ID');
            $table->unsignedBigInteger('variant_id')->nullable()->comment('变体ID（SKU ID）');

            // 媒体类型字段
            $table->string('media_type', 32)->comment(MediaTypeEnum::comments('媒体类型'));
            $table->string('mime_type', 100)->nullable()->comment('MIME类型');
            $table->string('media_id')->nullable()->comment('媒体资源ID');

            // 文件信息字段
            $table->string('path')->comment('文件路径（相对路径，自动拼接CDN地址）');
            $table->string('alt_text', 500)->nullable()->comment('替代文本');

            // 排序和标记字段
            $table->integer('position')->default(0)->comment('排序位置');
            $table->boolean('is_primary')->default(false)->comment('是否主图');
            $table->boolean('is_enabled')->default(true)->comment('是否启用');

            // 扩展字段
            $table->json('extra')->nullable()->comment('扩展字段');

            // 系统字段
            $table->operator();
            $table->softDeletes();

            $table->comment('商品-媒体资源表');

            // 索引设计
            $table->index(['owner_type', 'owner_id'], 'idx_owner');
            $table->index('product_id', 'idx_product');
            $table->index('variant_id', 'idx_variant');
            $table->index(['product_id', 'variant_id'], 'idx_product_variant');
            $table->index(['product_id', 'media_type'], 'idx_product_type');
            $table->index(['product_id', 'is_primary'], 'idx_product_primary');
            $table->index(['variant_id', 'is_primary'], 'idx_variant_primary');
            $table->index(['product_id', 'position'], 'idx_product_position');
            $table->index(['owner_type', 'owner_id', 'media_type'], 'idx_owner_type');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('product_media');
    }
};

