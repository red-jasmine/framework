<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Card\Domain\Enums\CardStatus;

return new class extends Migration {
    public function up() : void
    {
        Schema::create(config('red-jasmine-card.tables.prefix'). 'cards', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->morphs('owner');
            $table->unsignedBigInteger('group_id')->default(0)->comment('卡密分组ID');
            $table->boolean('is_loop')->default(false)->comment('是否循环');
            $table->string('status',32)->comment(CardStatus::comments('状态'));
            $table->timestamp('sold_time')->nullable()->comment('出售时间');
            $table->text('content')->comment('内容');
            $table->string('remarks')->nullable()->comment('备注');
            $table->nullableMorphs('creator');
            $table->nullableMorphs('updater');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('卡密表');
            $table->index([ 'group_id', 'status' ], 'idx_group_status');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists(config('red-jasmine-card.tables.prefix').'cards');
    }
};
