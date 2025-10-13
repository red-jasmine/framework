<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RedJasmine\UserCore\Infrastructure\Migrations\UserMigration;

return new class extends UserMigration {

    protected string $name  = 'member';
    protected string $label = '成员';

    public function up() : void
    {
        parent::up();

        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->unsignedBigInteger('org_id')->default(0)->comment('组织ID');
            $table->unsignedInteger('position_id')->nullable()->comment('职位ID');
            $table->unsignedBigInteger('leader_id')->nullable()->comment('上级ID');
            $table->unsignedBigInteger('main_department_id')->nullable()->comment('主部门ID');
            $table->json('departments')->nullable()->comment('当前有效部门ID集合(冗余)');


            $table->unique(['org_id', 'name']);
        });

    }

    public function down() : void
    {
        parent::down();
    }
};


