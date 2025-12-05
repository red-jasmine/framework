<?php

namespace RedJasmine\Product\UI\Http\Owner\Api\Controllers;

class Controller extends \RedJasmine\Support\UI\Http\Controllers\Controller
{
    // 继承父类的 getOwner() 和 getUser() 方法
    // 这些方法会从 request()->user() 中获取真实的认证用户信息
}
