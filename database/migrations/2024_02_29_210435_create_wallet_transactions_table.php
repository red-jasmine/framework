<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->unsignedTinyInteger('direction')->comment('金额方向');
            $table->decimal('amount', 12)->default(0)->comment('金额');
            $table->string('transaction_type')->comment('交易类型');
            $table->string('status')->nullable()->comment('状态');
            $table->string('title', 30)->nullable()->comment('标题');
            $table->string('description')->nullable()->comment('说明');
            $table->string('bill_type', 30)->nullable()->comment('账单类型');
            $table->string('business_id')->nullable()->comment('交易单号');
            $table->string('tags')->nullable()->comment('标签');
            $table->string('remarks')->nullable()->comment('备注');
            $table->json('extends')->nullable()->comment('扩展字段');
            $table->timestamps();
            $table->index('wallet_id');
            $table->comment('钱包交易表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_bills');
    }
};
