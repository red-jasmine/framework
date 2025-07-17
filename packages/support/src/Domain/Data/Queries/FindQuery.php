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
     * 创建一个新的实例，根据给定的ID和可选的请求对象
     *
     * 该方法主要用于通过请求对象或仅通过ID创建实例。如果提供了请求对象，它将ID添加到请求对象的偏移量中，
     * 然后使用该请求对象创建实例。如果没有提供请求对象，它只是使用提供的ID创建一个实例
     *
     * @param  mixed  $id  实例的ID
     * @param ?Request  $request  可选的请求对象，如果未提供，则使用紧凑的ID信息创建实例
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
