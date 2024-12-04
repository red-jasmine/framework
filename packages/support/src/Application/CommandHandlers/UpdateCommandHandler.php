<?php

namespace RedJasmine\Support\Application\CommandHandlers;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Support\Facades\ServiceContext;

class UpdateCommandHandler extends CommandHandler
{


    public function handle(Data $command) : ?Model
    {
        $this->setCommand($command);

        $this->setModel($this->getRepository()->find($command->id));
        // 开始数据库事务
        $this->beginDatabaseTransaction();
        try {

            // 对数据进行验证
            $this->hook('update.validate', $command, fn() => $this->validate($command));
            // 填充模型属性
            $this->hook('update.fill', $command, fn() => $this->fill($command));


            // 存储模型到仓库
            $this->getRepository()->update($this->model);

            $this->commitDatabaseTransaction();
        } catch (Throwable $throwable) {
            $this->rollBackDatabaseTransaction();
            throw $throwable;
        }
        return $this->model;
    }

    protected function validate(Data $command) : void
    {

    }

    protected function fill(Data $command) : void
    {

        if ($this->getService()::getTransformerClass()) {

            /**
             * @var TransformerInterface $transformer
             */
            $transformer = app($this->getService()::getTransformerClass());


            $this->model = $transformer->transform($command, $this->model);

        } else {
            $this->model->fill($command->all());
        }
    }


}
