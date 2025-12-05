<?php

namespace RedJasmine\Admin\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Admin\Domain\Data\AdminBaseInfoData;
use RedJasmine\Admin\Domain\Data\AdminData;
use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Foundation\Data\Data;

class AdminTransformer implements TransformerInterface
{
    /**
     * @param  Data|AdminBaseInfoData|AdminData  $data
     * @param  Model|Admin  $model
     *
     * @return Model|null
     */
    public function transform($data, $model) : Admin
    {
        /**
         * @var Admin $model
         * @var AdminBaseInfoData $data
         */
        $model->type      = $data->type;
        $model->gender    = $data->gender;
        $model->nickname  = $data->nickname;
        $model->birthday  = $data->birthday;
        $model->biography = $data->biography;
        $model->avatar    = $data->avatar;
        $model->country   = $data->country;
        $model->province  = $data->province;
        $model->city      = $data->city;
        $model->district  = $data->district;
        $model->school    = $data->school;


        if ($data instanceof AdminData) {
            $model->phone = $data->phone;
            $model->email = $data->email;
            $model->name  = $data->name;
        }

        return $model;
    }


}