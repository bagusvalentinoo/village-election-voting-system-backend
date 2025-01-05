<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Default Filter Trait
 *
 * @method Builder baseFilter(array $payload)
 */
trait BaseFilterModel
{
    /**
     * Default Filter Model
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBaseFilter(Builder $builder, array $payload): Builder
    {
        return $builder
            ->when($payload['ids'] ?? false, function ($query) use ($payload) {
                return $query->whereIn('id', $payload['ids']);
            })
            ->when($payload['select'] ?? false, function ($query) use ($payload) {
                return $query->select($payload['select']);
            });
    }
}
