<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Payment\Domain\Models\Enums\MethodStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('标识');
            $table->string('name')->comment('名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('status')->default(MethodStatusEnum::ENABLE->value)->comment(MethodStatusEnum::comments('状态'));
            $table->string('remarks')->nullable()->comment('备注');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->unique('code', 'uk_method');
            $table->comment('支付方式');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_methods');
    }
};
