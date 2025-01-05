<?php

namespace App\Models\User;

use App\Models\Election\ElectionResult;
use App\Models\Election\ElectionSession;
use App\Traits\BaseFilterModel;
use App\Traits\DefaultTimestampsFormat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Candidate Pair Model
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string id
 * @property string election_session_id
 * @property string first_candidate_name
 * @property string second_candidate_name
 * @property string description
 * @property string image_url
 * @property int number
 * @property int total_vote
 *
 * @method Builder forVoteCandidate(array $payload)
 */
class CandidatePair extends Model
{
    use HasUuids, DefaultTimestampsFormat, BaseFilterModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'election_session_id',
        'first_candidate_name',
        'second_candidate_name',
        'description',
        'image_url',
        'number',
        'total_vote'
    ];

    // Relation Methods
    // Belongs To
    /**
     * Get the election session that owns the voter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function election_session(): BelongsTo
    {
        return $this->belongsTo(ElectionSession::class, 'election_session_id', 'id');
    }

    // Has Many

    /**
     * Get the voters for the candidate pair
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class, 'selected_candidate_pair_id', 'id');
    }

    // Scope Methods

    /**
     * For Vote Candidate Scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForVoteCandidate(Builder $builder, array $payload): Builder
    {
        return $builder->where('election_session_id', $payload['election_session_id']);
    }
}
