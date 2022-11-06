<?php

namespace RedJasmine\Support\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Dcat\Admin\Layout\Content;

class HomeController extends Controller
{
    public function index(Content $content)
    {

        return $content
            ->header('Dashboard')
            ->description('Description...')
            ->body('body');

    }

}
