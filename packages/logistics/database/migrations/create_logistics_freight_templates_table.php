<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStrategyTypeEnum;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('logistics_freight_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('name')->comment('模板名称');
            $table->string('charge_type')->comment('计费类型');
            $table->boolean('is_free')->default(true)->comment('是否包邮');
            $table->unsignedBigInteger('sort')->default(0)->comment('排序');
            $table->string('status')->default(FreightTemplateStatusEnum::ENABLE)->comment(FreightTemplateStatusEnum::comments('状态'));
            $table->operator();

            $table->softDeletes();
            $table->comment('运费模板表');

        });

        Schema::create('logistics_freight_template_strategies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('template_id')->comment('模板ID');
            $table->boolean('is_all_regions')->default(false)->comment('全部区域');
            $table->string('type')->comment(FreightTemplateStrategyTypeEnum::comments('区域类型'));
            $table->decimal('standard_quantity')->default(0)->comment('标准数量');
            $table->string('standard_fee_currency', 3)->nullable()->comment('标准金额货币');
            $table->unsignedBigInteger('standard_fee_amount')->nullable()->comment('标准金额金额');
            $table->decimal('extra_quantity')->default(0)->comment('额外数量');
            $table->string('extra_fee_currency', 3)->nullable()->comment('额外金额货币');
            $table->unsignedBigInteger('extra_fee_amount')->nullable()->comment('额外金额金额');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('运费模板-策略表');
        });

        Schema::create('logistics_freight_template_strategy_regions', function (Blueprint $table) {
            $table->id()->comment('ID');
            $table->unsignedBigInteger('strategy_id')->comment('策略ID');
            $table->string('code')->comment('区域CODE');
            $table->timestamps();
            $table->comment('运费模板-策略区域表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('logistics_freight_templates');
        Schema::dropIfExists('logistics_freight_template_strategies');
        Schema::dropIfExists('logistics_freight_template_strategy_regions');
    }
};
