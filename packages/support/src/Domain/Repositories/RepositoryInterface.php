<?php

namespace RedJasmine\Support\Domain\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * RepositoryInterface 定义了一个仓储接口，旨在提供一套标准的方法来交互数据存储。
 * 该接口主要用于抽象数据访问层，通过定义通用的数据操作方法如查找、存储、更新和删除，
 * 使得具体的数据操作实现可以适配不同的数据存储机制，同时提供统一的操作接口给上层业务逻辑使用。
 */
interface RepositoryInterface
{
    /**
     * 查找指定ID的记录。
     *
     * @param mixed $id 要查找的记录的ID。
     * @return mixed 找到的记录，具体返回类型取决于实现。
     */
    public function find($id);




    /**
     * 存储一个模型实例到数据库。
     *
     * @param Model $model 要存储的模型实例。
     * @return mixed 存储操作的结果，具体返回类型取决于实现。
     */
    public function store(Model $model);

    /**
     * 更新一个模型实例的数据。
     *
     * @param Model $model 要更新的模型实例，通常已经加载了需要更新的数据。
     * @return mixed 更新操作的结果，具体返回类型取决于实现。
     */
    public function update(Model $model);

    /**
     * 从数据库中删除一个模型实例。
     *
     * @param Model $model 要删除的模型实例。
     * @return mixed 删除操作的结果，具体返回类型取决于实现。
     */
    public function delete(Model $model);
}
