<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Card\Domain\Enums\CardStatus;

return new class extends Migration {
    public function up() : void
    {
        Schema::create( 'cards', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('owner_type', 64);
            $table->string('owner_id', 64);
            $table->unsignedBigInteger('group_id')->default(0)->comment('卡密分组ID');
            $table->boolean('is_loop')->default(false)->comment('是否循环');
            $table->string('status',32)->comment(CardStatus::comments('状态'));
            $table->timestamp('sold_time')->nullable()->comment('出售时间');
            $table->text('content')->comment('内容');
            $table->string('remarks')->nullable()->comment('备注');

            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('creator_nickname', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->string('updater_nickname', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->comment('卡密表');
            $table->index([ 'group_id', 'status' ], 'idx_group_status');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('cards');
    }
};
