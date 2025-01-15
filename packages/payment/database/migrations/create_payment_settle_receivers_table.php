<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_settle_receivers',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary()->comment('ID');
                $table->unsignedBigInteger('system_merchant_app_id')->comment('系统商户ID');
                $table->string('relation_type')->comment(SettleRelationTypeEnum::comments('关系类型'));
                $table->string('name')->comment('名称');
                $table->string('cert_type')->nullable()->comment(CertTypeEnum::comments('证件类型'));
                $table->string('cert_no')->nullable()->comment('收款方证件号');

                $table->string('creator_type', 32)->nullable();
                $table->string('creator_id', 64)->nullable();
                $table->string('updater_type', 32)->nullable();
                $table->string('updater_id', 64)->nullable();
                $table->timestamps();
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix',
                                    'jasmine_') . 'payment_settle_receivers');
    }
};
