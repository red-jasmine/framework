<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-order.tables.prefix', 'jasmine_').'order_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('order_no', 64)->comment('订单号');
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
            $table->string('tag')->nullable()->comment('标签');
            $table->json('extras')->nullable()->comment('扩展');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable()->comment('创建者类型');
            $table->string('creator_id', 64)->nullable()->comment('创建者ID');
            $table->string('updater_type', 64)->nullable()->comment('更新者类型');
            $table->string('updater_id', 64)->nullable()->comment('更新者ID');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('订单-地址表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-order.tables.prefix', 'jasmine_').'order_addresses');
    }
};
