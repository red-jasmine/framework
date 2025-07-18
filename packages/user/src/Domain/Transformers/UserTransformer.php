<?php

namespace RedJasmine\User\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\User\Domain\Data\UserBaseInfoData;
use RedJasmine\User\Domain\Data\UserData;
use RedJasmine\User\Domain\Models\User;

class UserTransformer implements TransformerInterface
{
    /**
     * @param  UserBaseInfoData|UserData  $data
     * @param  User  $model
     *
     * @return User
     */
    public function transform($data, $model) : User
    {
        /**
         * @var User $model
         */

        if ($data instanceof UserBaseInfoData) {
            $model->avatar    = $data->avatar;
            $model->nickname  = $data->nickname;
            $model->gender    = $data->gender;
            $model->birthday  = $data->birthday;
            $model->biography = $data->biography;
            $model->country   = $data->country;
            $model->province  = $data->province;
            $model->city      = $data->city;
            $model->district  = $data->district;
            $model->school    = $data->school;

        }
        if ($data instanceof UserData) {
            $model->account_type = $data->accountType;
            $model->phone        = $data->phone;
            $model->email        = $data->email;
            $model->name         = $data->name;
            $model->password     = $data->password;
        }

        return $model;
    }


}