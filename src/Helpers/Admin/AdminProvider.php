<?php

namespace RedJasmine\Support\Helpers\Admin;

use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Column;
use RedJasmine\Support\Enums\UserTypeEnum;
use RedJasmine\Support\Helpers\Admin\Extends\Enums;
use RedJasmine\Support\Helpers\Admin\Extends\Select;

class AdminProvider
{


    public static function boot() : void
    {
        Column::extend('select', Select::class);
        Column::extend('enums', Enums::class);


        self::adminer();
    }


    /**
     * 管理端 添加创建和更新人
     * @return void
     */
    public static function adminer() : void
    {
        Form::macro('adminer', function () {
            if ($this->isCreating()) {
                $this->creator_type = UserTypeEnum::ADMIN->value;
                $this->creator_uid  = Admin::user()->id;
            }
            if ($this->isEditing()) {
                $this->updater_type = UserTypeEnum::ADMIN->value;
                $this->updater_uid  = Admin::user()->id;
            }
        });
    }

}
