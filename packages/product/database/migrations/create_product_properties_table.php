<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyStatusEnum;
use RedJasmine\Product\Domain\Property\Models\Enums\PropertyTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'product_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('属性项ID');
            $table->unsignedBigInteger('group_id')->default(0)->comment('属性组ID');
            $table->string('type', 32)->comment(PropertyTypeEnum::comments('类型'));
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('unit', 10)->nullable()->comment('单位');
            $table->boolean('is_required')->default(false)->comment('是否必选');
            $table->boolean('is_allow_multiple')->default(false)->comment('是否多值');
            $table->boolean('is_allow_alias')->default(false)->comment('是否允许别名');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->comment(PropertyStatusEnum::comments('状态'));

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('group_id', 'idx_group');
            $table->comment('商品-属性项表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists( 'product_properties');
    }
};
