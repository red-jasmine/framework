<?php

namespace RedJasmine\Support\Domain\Data\Queries;

use Illuminate\Http\Request;

class FindQuery extends Query
{
    public mixed $include;

    public mixed $fields;

    public mixed $append;

    public mixed $id;

    /**
     * 根据请求和ID创建实例
     *
     * 该方法从一个HTTP请求和指定ID创建一个新的业务对象实例主要用途是在接收到HTTP请求时，
     * 根据请求中的数据以及额外提供的ID来初始化业务对象这样，可以在应用程序中根据HTTP请求
     * 路由轻松地创建和管理业务对象实例
     *
     * @param  Request  $request  The request object representing the HTTP request
     * @param  mixed  $id  The identifier to be associated with the business object
     *
     * @return static An instance of the business object initialized with data from the request and the provided ID
     */
    public static function fromRequestRoute(Request $request, $id) : static
    {
        $request->offsetSet('id', $id);
        return static::from($request);
    }


}
