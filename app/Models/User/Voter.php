<?php

namespace App\Models\User;

use App\Helpers\Model\VoterHelper;
use App\Models\Election\ElectionSession;
use App\Traits\BaseFilterModel;
use App\Traits\DefaultTimestampsFormat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Voter Model
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property string id
 * @property string election_session_id
 * @property string nik
 * @property string full_name
 * @property string birth_date
 * @property string address
 * @property string gender
 * @property string otp
 * @property string otp_status
 *
 * @property ElectionSession election_session
 */
class Voter extends Model
{
    use HasUuids, DefaultTimestampsFormat, BaseFilterModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'election_session_id',
        'nik',
        'full_name',
        'birth_date',
        'address',
        'gender',
        'otp',
        'otp_status',
        'selected_candidate_pair_id'
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
        return $builder->where('otp', $payload['otp'] ?? null)
            ->where('otp_status', VoterHelper::OTP_STATUSES['not_used'])
            ->whereHas('election_session', function ($query) use ($payload) {
                return $query->ongoing();
            });
    }
}
