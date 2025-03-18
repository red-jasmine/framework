<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Wallet\Domain\Models\Enums\WalletStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-support.tables.prefix', 'jasmine_').'wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->string('type', 32)->comment('账户类型');
            $table->string('owner_type', 32)->comment('所属者类型');
            $table->string('owner_id', 64)->comment('所属者ID');
            $table->string('currency', 3)->default('CNY')->comment('货币');
            $table->decimal('balance', 12)->default(0)->comment('余额');
            $table->decimal('freeze', 12)->default(0)->comment('冻结');
            $table->string('status', 32)->default(WalletStatusEnum::ENABLE)->comment(WalletStatusEnum::comments('状态'));
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 32)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 32)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->unique(['owner_type', 'owner_id', 'type'], 'owner_type_wallet');
            $table->comment('钱包表');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-support.tables.prefix', 'jasmine_').'wallets');
    }
};
