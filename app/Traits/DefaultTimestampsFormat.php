<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

/**
 * Trait DefaultTimestampsFormat
 *
 * @property string created_at
 * @property string updated_at
 */
trait DefaultTimestampsFormat
{
    /**
     * Get the created_at attribute
     *
     * @param $value
     * @return string|null
     */
    public function getCreatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format(config('app.timestamp_format')) : null;
    }

    /**
     * Get the updated_at attribute
     *
     * @param $value
     * @return string|null
     */
    public function getUpdatedAtAttribute($value): ?string
    {
        return $value ? Carbon::parse($value)->format(config('app.timestamp_format')) : null;
    }
}
