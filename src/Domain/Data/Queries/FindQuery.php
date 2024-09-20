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



    /**
     * 创建一个新的实例，根据给定的ID和可选的请求对象
     *
     * 该方法主要用于通过请求对象或仅通过ID创建实例。如果提供了请求对象，它将ID添加到请求对象的偏移量中，
     * 然后使用该请求对象创建实例。如果没有提供请求对象，它只是使用提供的ID创建一个实例
     *
     * @param mixed $id 实例的ID
     * @param ?Request $request 可选的请求对象，如果未提供，则使用紧凑的ID信息创建实例
     *
     * @return static 返回新创建的实例
     */
    public static function make(mixed $id, ?Request $request = null) : static
    {
        // 如果提供了请求对象
        if ($request) {
            // 将ID设置为请求对象的属性
            $request->offsetSet('id', $id);
            // 使用请求对象创建并返回实例
            return static::from($request);
        }
        // 如果没有提供请求对象，仅使用ID创建并返回实例
        return static::from(compact('id'));
    }


}
