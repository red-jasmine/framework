<?php
/*
 * @Author: liushoukun liushoukun66@gmail.com
 * @Date: 2025-06-17 22:25:01
 * @LastEditors: liushoukun liushoukun66@gmail.com
 * @LastEditTime: 2025-06-17 22:25:25
 * @FilePath: \framework\packages\distribution\src\Domain\Repositories\PromoterRepositoryInterface.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Distribution\Domain\Models\PromoterBindUser;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PromoterRepositoryInterface extends RepositoryInterface, PromoterReadRepositoryInterface
{
   
    
}