<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-product.tables.prefix') . 'product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('属性组ID');
            $table->string('type', 32)->comment(PropertyTypeEnum::comments('类型'));
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('unit', 10)->nullable()->comment('单位');
            $table->unsignedTinyInteger('is_required')->default(0)->comment('是否必选');
            $table->unsignedTinyInteger('is_allow_multiple')->default(0)->comment('是否多值');
            $table->unsignedTinyInteger('is_allow_alias')->default(0)->comment('是否允许别名');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(PropertyStatusEnum::comments('状态'));
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('商品-属性表');
            $table->index('group_id', 'idx_group');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-product.tables.prefix') . 'product_properties');
    }
};
