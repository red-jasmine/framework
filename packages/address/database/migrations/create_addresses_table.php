<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Address\Domain\Models\Enums\AddressStatusEnum;

return new class extends Migration {
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 64)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('contacts', 500)->nullable()->comment('联系人*');
            $table->string('phone', 500)->nullable()->comment('电话*');
            $table->string('country', 64)->nullable()->comment('国家');
            $table->string('province', 64)->nullable()->comment('省份');
            $table->string('city', 64)->nullable()->comment('城市');
            $table->string('district', 64)->nullable()->comment('区县');
            $table->string('street', 64)->nullable()->comment('乡镇街道');
            $table->string('village', 64)->nullable()->comment('村庄');
            $table->string('company')->nullable()->comment('公司');
            $table->text('address')->nullable()->comment('详细地址*');
            $table->text('more_address')->nullable()->comment('更多地址*');
            $table->string('postcode', 32)->nullable()->comment('邮政编码');
            $table->string('country_code', 32)->nullable()->comment('国家编码');
            $table->string('province_code', 32)->nullable()->comment('省份编码');
            $table->string('city_code', 32)->nullable()->comment('城市编码');
            $table->string('district_code', 32)->nullable()->comment('区县编码');
            $table->string('street_code', 32)->nullable()->comment('乡镇街道编码');
            $table->string('village_code', 32)->nullable()->comment('村庄编码');
            $table->decimal('longitude', 11, 8)->nullable()->comment('经度');
            $table->decimal('latitude', 10, 8)->nullable()->comment('纬度');
            $table->string('remarks')->nullable()->comment('备注');
            $table->string('type')->nullable()->comment('地址类型');
            $table->boolean('is_default')->default(false)->comment('是否默认');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('status', 32)->default(AddressStatusEnum::ENABLE)->comment(AddressStatusEnum::comments('状态'));
            $table->string('tag')->nullable()->comment('标签');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->comment('地址表');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
