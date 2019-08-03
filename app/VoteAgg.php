<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder as Builder;

/**
 * App\VoteAgg
 *
 * @property int $proposal_id
 * @property float|null $sum_up
 * @property float|null $sum_down
 * @method static Builder|\App\VoteAgg newModelQuery()
 * @method static Builder|\App\VoteAgg newQuery()
 * @method static Builder|\App\VoteAgg query()
 * @method static Builder|\App\VoteAgg whereProposalId($value)
 * @method static Builder|\App\VoteAgg whereSumDown($value)
 * @method static Builder|\App\VoteAgg whereSumUp($value)
 * @mixin \Eloquent
 * @property float|null $total_votes
 * @property-read \App\Proposal $proposal
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoteAgg whereTotalVotes($value)
 * @property float|null $total_dislikes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoteAgg whereTotalDislikes($value)
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
        return $this->belongsTo('App\Proposal');
    }
}
