<?php

namespace RedJasmine\Ecommerce\Domain\Contracts;

use RedJasmine\Support\Contracts\UserInterface;

/**
 * 运费模板服务接口
 *
 */
interface FreightTemplateServiceInterface
{
    /**
     * 获取运费模板
     *
     * @param $id
     *
     * @return string
     */
    public function getFreightTemplate($id) : string;


    /**
     * 通过用户获取运费模板
     *
     * @param  UserInterface  $owner
     * @param $id
     *
     * @return mixed
     */
    public function findFreightTemplateByOwner(UserInterface $owner, $id);


    /**
     * 获取运费模板列表
     *
     * @param  UserInterface  $owner
     *
     * @return array
     */
    public function getFreightTemplateListByOwner(UserInterface $owner) : array;

}