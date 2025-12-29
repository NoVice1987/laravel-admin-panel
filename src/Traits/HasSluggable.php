<?php

namespace StatisticLv\AdminPanel\Traits;

use Illuminate\Support\Str;

trait HasSluggable
{
    /**
     * Boot the sluggable trait for a model.
     *
     * @return void
     */
    protected static function bootHasSluggable()
    {
        static::creating(function ($model) {
            $slugField = $model->slugField ?? 'slug';
            $sourceField = $model->sourceField ?? 'name';
            
            if (empty($model->$slugField) && !empty($model->$sourceField)) {
                $model->$slugField = Str::slug($model->$sourceField);
            }
        });

        static::updating(function ($model) {
            $slugField = $model->slugField ?? 'slug';
            $sourceField = $model->sourceField ?? 'name';
            
            if ($model->isDirty($sourceField) && empty($model->$slugField)) {
                $model->$slugField = Str::slug($model->$sourceField);
            }
        });
    }
}
