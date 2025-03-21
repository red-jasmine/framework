<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('transaction_no', 64)->unique()->comment('交易号');
            $table->unsignedBigInteger('wallet_id')->comment('钱包ID');
            $table->string('wallet_type')->comment('钱包类型');
            $table->string('direction')->comment(AmountDirectionEnum::comments('金额方向'));
            $table->string('amount_currency', 3)->comment('货币');
            $table->decimal('amount_total', 12)->comment('金额');
            $table->decimal('balance', 12)->comment('余额');
            $table->decimal('freeze', 12)->comment('冻结');
            $table->string('status')->default(TransactionStatusEnum::SUCCESS)->comment(TransactionStatusEnum::comments('状态'));
            $table->timestamp('trade_time')->comment('交易时间');


            $table->string('app_id', 64)->comment('应用ID');
            $table->string('transaction_type', 32)->comment(TransactionTypeEnum::comments('交易类型'));
            $table->string('title', 64)->nullable()->comment('标题');
            $table->string('description')->nullable()->comment('说明');
            $table->string('bill_type', 30)->nullable()->comment('账单类型');
            $table->string('out_trade_no')->nullable()->comment('业务单号');
            $table->string('tags')->nullable()->comment('标签');
            $table->string('remarks')->nullable()->comment('备注');
            $table->json('extras')->nullable()->comment('扩展字段');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();

            $table->index('wallet_id', 'idx_wallet_id');
            $table->comment('钱包-交易表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
