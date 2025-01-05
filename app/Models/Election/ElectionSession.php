<?php

namespace App\Models\Election;

use App\Models\User\CandidatePair;
use App\Models\User\Voter;
use App\Traits\BaseFilterModel;
use App\Traits\DefaultTimestampsFormat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Election Session Model
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string id
 * @property string name
 * @property string start_date
 * @property string end_date
 * @property Collection<CandidatePair> candidate_pairs
 *
 * @method Builder ongoing()
 */
class ElectionSession extends Model
{
    use HasUuids, DefaultTimestampsFormat, BaseFilterModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date'
    ];

    // Accessor Methods

    /**
     * Get the start date attribute
     *
     * @param $value
     * @return string
     */
    public function getStartDateAttribute($value): string
    {
        return Carbon::parse($value)->format(config('app.timestamp_format'));
    }

    /**
     * Get the end date attribute
     *
     * @param $value
     * @return string
     */
    public function getEndDateAttribute($value): string
    {
        return Carbon::parse($value)->format(config('app.timestamp_format'));
    }

    // Relation Methods
    // Has Many
    /**
     * Get the candidate pairs for the election session
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidate_pairs(): HasMany
    {
        return $this->hasMany(CandidatePair::class, 'election_session_id', 'id');
    }

    /**
     * Get the voters for the election session
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class, 'election_session_id', 'id');
    }

    // Scope Methods

    /**
     * Ongoing Scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOngoing(Builder $builder): Builder
    {
        return $builder->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * For Ongoing Voting Scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOngoingVoting(Builder $builder): Builder
    {
        return $builder->ongoing()
            ->with([
                'candidate_pairs' => function ($query) {
                    return $query->orderBy('number', 'asc');
                }
            ]);
    }

    /**
     * For Ongoing Result Scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForOngoingResult(Builder $builder): Builder
    {
        return $builder->ongoing()
            ->with([
                'candidate_pairs' => function ($query) {
                    return $query->orderBy('number', 'asc');
                }
            ]);
    }
}
