<?php

namespace RedJasmine\User\Infrastructure\Migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\UserCore\Infrastructure\Migrations\UserMigration;

abstract class Migration extends UserMigration
{

    protected string $name  = 'user';
    protected string $label = '用户';

    public function up() : void
    {
        parent::up();

        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->unique(['name'],'uk_name');
        });
        Schema::create($this->name.'_groups', function (Blueprint $table) {
            $table->category($this->label.'分组');
        });

        Schema::create($this->name.'_tags', function (Blueprint $table) {
            $table->category($this->label.'标签');
        });

        Schema::create($this->name.'_tag_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
            $table->index('owner_id', 'idx_owner');
            $table->index('tag_id', 'idx_tag');
            $table->comment($this->label.'标签关联表');
        });
    }

    public function down() : void
    {
        parent::down();

        Schema::dropIfExists($this->name.'_groups');

        Schema::dropIfExists($this->name.'_tags');

        Schema::dropIfExists($this->name.'_tag_pivot');
    }
}
