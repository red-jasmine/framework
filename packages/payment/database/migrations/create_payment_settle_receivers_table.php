<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receiver_accounts',
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->unsignedBigInteger('merchant_id')->comment('商户ID');
                $table->string('relation_type')->comment('关系类型');
                $table->string('name')->comment('名称');
                $table->string('cert_type')->nullable()->comment('收款证件类型');
                $table->string('cert_no')->nullable()->comment('收款方证件号');
                $table->timestamps();
            });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-payment.tables.prefix',
                'jasmine_').'payment_settle_receiver_accounts');
    }
};
