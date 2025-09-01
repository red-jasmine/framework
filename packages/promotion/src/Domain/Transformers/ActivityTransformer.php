<?php

namespace RedJasmine\Promotion\Domain\Transformers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Promotion\Domain\Data\ActivityData;
use RedJasmine\Promotion\Domain\Models\Activity;
use RedJasmine\Promotion\Domain\Services\ActivityTypeHandlerFactory;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;

/**
 * 活动转换器
 */
class ActivityTransformer implements TransformerInterface
{
    /**
     * 将ActivityData转换为Activity模型
     * 
     * @param ActivityData $data
     * @param Activity|null $model
     * @return Activity
     */
    public function transform($data, $model = null): Model
    {
        if (!$model) {
            $model = new Activity();
        }
        
        // 基础字段转换
        $model->title = $data->title;
        $model->description = $data->description;
        $model->type = $data->type;
        $model->sign_up_start_time = $data->signUpStartTime;
        $model->sign_up_end_time = $data->signUpEndTime;
        $model->start_time = $data->startTime;
        $model->end_time = $data->endTime;
        $model->status = $data->status;
        $model->is_show = $data->isShow;
        
        // 设置所属者信息
        if (isset($data->owner)) {
            $model->owner_type = get_class($data->owner);
            $model->owner_id = $data->owner->getID();
        }
        
        // 转换需求对象
        $model->product_requirements = $data->productRequirements;
        $model->shop_requirements = $data->shopRequirements;
        $model->user_requirements = $data->userRequirements;
        
        // 处理活动规则
        $this->transformActivityRules($data, $model);
        
        return $model;
    }
    
    /**
     * 转换活动规则
     * 
     * @param ActivityData $data
     * @param Activity $model
     * @return void
     */
    protected function transformActivityRules(ActivityData $data, Activity $model): void
    {
        // 获取活动类型处理器
        $handler = ActivityTypeHandlerFactory::make($data->type->value);
        
        // 合并默认规则和用户定义规则
        $defaultRules = $handler->getDefaultRules();
        $userRules = $data->rules ? $data->rules->toArray() : [];
        
        $mergedRules = array_merge($defaultRules, $userRules);
        
        // 验证规则
        $this->validateRules($mergedRules, $handler->getExtensionFields());
        
        $model->rules = $mergedRules;
    }
    
    /**
     * 验证规则配置
     * 
     * @param array $rules
     * @param array $extensionFields
     * @return void
     */
    protected function validateRules(array $rules, array $extensionFields): void
    {
        foreach ($rules as $key => $value) {
            // 检查是否为已知的扩展字段
            if (isset($extensionFields[$key])) {
                $expectedType = $extensionFields[$key];
                
                // 简单的类型验证
                switch ($expectedType) {
                    case 'integer':
                        if (!is_null($value) && !is_int($value)) {
                            throw new \InvalidArgumentException("规则字段 {$key} 必须是整数类型");
                        }
                        break;
                    case 'boolean':
                        if (!is_null($value) && !is_bool($value)) {
                            throw new \InvalidArgumentException("规则字段 {$key} 必须是布尔类型");
                        }
                        break;
                    case 'string':
                        if (!is_null($value) && !is_string($value)) {
                            throw new \InvalidArgumentException("规则字段 {$key} 必须是字符串类型");
                        }
                        break;
                    case str_starts_with($expectedType, 'decimal'):
                        if (!is_null($value) && !is_numeric($value)) {
                            throw new \InvalidArgumentException("规则字段 {$key} 必须是数字类型");
                        }
                        break;
                    case 'json':
                    case 'array':
                        if (!is_null($value) && !is_array($value)) {
                            throw new \InvalidArgumentException("规则字段 {$key} 必须是数组类型");
                        }
                        break;
                }
            }
        }
    }
    
    /**
     * 将Activity模型转换为ActivityData
     * 
     * @param Activity $model
     * @return ActivityData
     */
    public function reverse(Activity $model): ActivityData
    {
        return ActivityData::from([
            'title' => $model->title,
            'description' => $model->description,
            'type' => $model->type,
            'sign_up_start_time' => $model->sign_up_start_time,
            'sign_up_end_time' => $model->sign_up_end_time,
            'start_time' => $model->start_time,
            'end_time' => $model->end_time,
            'product_requirements' => $model->product_requirements,
            'shop_requirements' => $model->shop_requirements,
            'user_requirements' => $model->user_requirements,
            'rules' => $model->rules,
            'status' => $model->status,
            'is_show' => $model->is_show,
        ]);
    }
}
