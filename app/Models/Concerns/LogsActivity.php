<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            self::record($model, 'created', $model->getAttributes());
        });

        static::updated(function ($model) {
            self::record($model, 'updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            self::record($model, 'deleted', $model->getAttributes());
        });
    }

    protected static function record($model, string $action, array $changes): void
    {
        unset($changes['updated_at'], $changes['created_at'], $changes['password']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}
