<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('type', 32)->comment('账户类型');
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('balance', 12)->default(0)->comment('余额');
            $table->decimal('freeze', 12)->default(0)->comment('冻结');
            $table->string('status', 32)->default(WalletStatusEnum::ENABLE)->comment(WalletStatusEnum::comments('状态'));

            $table->operator();
            $table->unique(['owner_type', 'owner_id', 'type'], 'owner_type_wallet');
            $table->comment('钱包表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('wallets');
    }
};
