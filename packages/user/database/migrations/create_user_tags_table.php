<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\User\Domain\Enums\UserTagStatusEnum;

return new class extends Migration {
    public function up() : void
    {
        Schema::create('user_tags', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type',64);
            $table->string('owner_id',64);
            $table->unsignedBigInteger('category_id')->nullable()->comment('分类ID');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('color')->nullable()->comment('颜色');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->string('status', 32)->default(UserTagStatusEnum::ENABLE)->comment(UserTagStatusEnum::comments('状态'));
            $table->json('extra')->nullable()->comment('扩展信息');
            $table->unsignedBigInteger('version')->default(0)->comment('版本');
            $table->string('creator_type', 64)->nullable();
            $table->string('creator_id', 64)->nullable();
            $table->string('updater_type', 64)->nullable();
            $table->string('updater_id', 64)->nullable();
            $table->timestamps();
            $table->comment('用户标签');
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('user_tags');
    }
};
