<?php

namespace RedJasmine\Announcement\Domain\Transformers;

use Carbon\Carbon;
use RedJasmine\Support\Domain\Transformer\TransformerInterface;
use RedJasmine\Announcement\Domain\Models\Announcement;
use RedJasmine\Announcement\Domain\Data\AnnouncementData;

class AnnouncementTransformer implements TransformerInterface
{
    public function transform($data, $model) : Announcement
    {
        if (!$model instanceof Announcement) {
            $model = new Announcement();
        }

        if ($data instanceof AnnouncementData) {
            $model->biz             = $data->biz;
            $model->owner           = $data->owner;
            $model->category_id     = $data->categoryId;
            $model->title           = $data->title;
            $model->image           = $data->image;
            $model->content_type    = $data->contentType;
            $model->content         = $data->content;
            $model->scopes          = $data->scopes;
            $model->channels        = $data->channels;
            $model->publish_time    = $data->publishTime ? Carbon::parse($data->publishTime) : null;
            $model->status          = $data->status;
            $model->attachments     = $data->attachments;
            $model->is_force_read   = $data->isForceRead;
        }

        return $model;
    }
}
