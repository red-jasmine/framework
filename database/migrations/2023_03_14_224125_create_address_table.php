<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type',20)->comment('所属者类型');
            $table->string('owner_uid',64)->comment('所属者UID');


            $table->string('contacts', 30)->nullable()->comment('联系人');
            $table->string('mobile')->nullable()->comment('手机');
            $table->string('phone')->nullable()->comment('电话');
            $table->unsignedBigInteger('country_id')->nullable()->comment('国家ID');
            $table->unsignedBigInteger('province_id')->nullable()->comment('省份ID');
            $table->unsignedBigInteger('city_id')->nullable()->comment('城市ID');
            $table->unsignedBigInteger('district_id')->nullable()->comment('区县ID');
            $table->unsignedBigInteger('street_id')->nullable()->comment('乡镇街道ID');

            $table->string('country', 20)->nullable()->comment('国家');
            $table->string('province', 20)->nullable()->comment('省份');
            $table->string('city', 30)->nullable()->comment('城市');
            $table->string('district', 40)->nullable()->comment('区县');
            $table->string('street', 50)->nullable()->comment('乡镇街道');

            $table->string('address')->nullable()->comment('详细地址');
            $table->string('zip_code', 10)->nullable()->comment('邮政编码');
            $table->decimal('lng', 13, 10)->nullable()->comment('经度');
            $table->decimal('lat', 13, 10)->nullable()->comment('纬度');


            $table->string('tag', 10)->nullable()->comment('标签');
            $table->string('remarks')->nullable()->comment('备注');

            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认');
            $table->integer('sort')->default(0)->comment('排序');

            $table->string('creator_type')->comment('创建者类型');
            $table->string('creator_uid')->comment('创建者ID');
            $table->string('updater_type')->nullable()->comment('更新者类型');
            $table->string('updater_uid')->nullable()->comment('更新者UID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('地址表');

        });
    }

    public function down()
    {
        Schema::dropIfExists('address');
    }
};
