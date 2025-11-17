<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique()->comment('仓库编码');
            $table->string('name', 255)->comment('仓库名称');

            // ========== 所属者信息 ==========
            $table->string('owner_type', 64)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');

            // ========== 仓库信息 ==========
            $table->string('warehouse_type', 32)->default('warehouse')->comment('类型：warehouse-仓库, store-门店, distribution_center-配送中心');
            $table->text('address')->nullable()->comment('地址');
            $table->string('contact_phone', 32)->nullable()->comment('联系电话');
            $table->string('contact_person', 64)->nullable()->comment('联系人');

            // ========== 状态 ==========
            $table->boolean('is_active')->default(true)->comment('是否启用');
            $table->boolean('is_default')->default(false)->comment('是否默认仓库');


            $table->operator();
            $table->softDeletes();

            $table->index('warehouse_type');
            $table->index('code');
            $table->index(['owner_id', 'owner_type'], 'idx_owner');

            $table->comment('仓库/位置表（轻量级）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};

