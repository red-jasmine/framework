<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('标识');
            $table->string('name')->comment('平台名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator','idx_creator');
            $table->nullableMorphs('updater','idx_updater');
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
