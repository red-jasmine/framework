<?php

namespace RedJasmine\Support\Domain\Models\Traits;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property static $tagModelClass
 * @property static $tagTable
 */
trait HasTags
{
    public function initializeHasTags() : void
    {
        static::saving(callback: function ($model) {
            if ($model->relationLoaded('tags')) {

                if ($model->tags?->count() > 0) {
                    if (!is_array($model->tags->first())) {
                        $model->tags()->sync($model->tags);
                    } else {
                        $model->tags()->sync($model->tags->pluck('id')->toArray());
                    }

                } else {
                    $model->tags()->sync([]);
                }
                $model->load('tags');
            }
        });
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(
            static::$tagModelClass,
            static::$tagTable,
            'owner_id',
            'tag_id'
        )->withTimestamps();
    }
}