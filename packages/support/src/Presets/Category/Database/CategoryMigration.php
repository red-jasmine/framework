<?php

namespace RedJasmine\Support\Presets\Category\Database;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;

abstract class CategoryMigration extends Migration
{

    protected string $table;
    protected string $comment;

    public function up() : void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->comment('ID');
            $table->unsignedBigInteger('parent_id')->default(0)->comment('父级ID');
            $table->string('slug')->nullable()->comment('标记');
            $table->string('name')->comment('名称');
            $table->string('description')->nullable()->comment('描述');
            $table->string('cluster')->nullable()->comment('群簇');
            $table->bigInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_leaf')->default(false)->comment('是否叶子');
            $table->boolean('is_show')->default(false)->comment('是否展示');
            $table->string('status', 32)->comment(UniversalStatusEnum::comments('状态'));
            $table->string('image')->nullable()->comment('图片');
            $table->string('icon')->nullable()->comment('图标');
            $table->string('color')->nullable()->comment('颜色');
            $table->json('extra')->nullable()->comment('扩展字段');

            $table->operator();
            $table->softDeletes();

            $table->index(['parent_id'], 'idx_parent');
            $table->index(['name'], 'idx_name');

            $table->comment($this->comment);


        });
    }

    public function down() : void
    {
        Schema::dropIfExists($this->table);
    }
}