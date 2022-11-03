<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VoteAgg
 *
 * @property int $proposal_id
 * @property float|null $sum_up
 * @property float|null $sum_down
 * @method static Builder|VoteAgg newModelQuery()
 * @method static Builder|VoteAgg newQuery()
 * @method static Builder|VoteAgg query()
 * @method static Builder|VoteAgg whereProposalId($value)
 * @method static Builder|VoteAgg whereSumDown($value)
 * @method static Builder|VoteAgg whereSumUp($value)
 * @mixin \Eloquent
 * @property float|null $total_votes
 * @property-read Models\Proposal $proposal
 * @method static \Illuminate\Database\Eloquent\Builder|VoteAgg whereTotalVotes($value)
 * @property float|null $total_dislikes
 * @method static \Illuminate\Database\Eloquent\Builder|VoteAgg whereTotalDislikes($value)
 */
class VoteAgg extends Model
{
    //
    /**
     * Get category it belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }
}
