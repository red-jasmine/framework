<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirection;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-support.tables.prefix', 'jasmine_').'wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->decimal('amount', 12)->comment('金额');
            $table->string('direction')->comment(AmountDirection::comments('金额方向'));
            $table->string('transaction_type')->comment(TransactionTypeEnum::comments('交易类型'));
            $table->string('status')->nullable()->comment(TransactionStatusEnum::comments('状态'));
            $table->string('title', 30)->nullable()->comment('标题');
            $table->string('description')->nullable()->comment('说明');
            $table->string('bill_type', 30)->nullable()->comment('账单类型');
            $table->string('order_no')->nullable()->comment('业务单号');
            $table->string('tags')->nullable()->comment('标签');
            $table->string('remarks')->nullable()->comment('备注');
            $table->timestamps();
            $table->index('wallet_id');
            $table->comment('钱包交易表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-support.tables.prefix', 'jasmine_').'wallet_transactions');
    }
};
