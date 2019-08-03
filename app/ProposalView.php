<?php

namespace App;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * App\ProposalView
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $category_id
 * @property string $colour
 * @method static Builder|Proposal newModelQuery()
 * @method static Builder|Proposal newQuery()
 * @method static Builder|Proposal query()
 * @method static Builder|Proposal whereBody($value)
 * @method static Builder|Proposal whereCategoryId($value)
 * @method static Builder|Proposal whereColour($value)
 * @method static Builder|Proposal whereCreatedAt($value)
 * @method static Builder|Proposal whereId($value)
 * @method static Builder|Proposal whereTitle($value)
 * @method static Builder|Proposal whereUpdatedAt($value)
 * @method static Builder|Proposal whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\User $user
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VoteAgg[] $aggs
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProposalView whereSlug($value)
 */
class ProposalView extends Model implements Feedable
{
    protected $table = 'proposals_view';
    
    /**
     * Return the user associated with the Proposal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get category it belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    /**
     * Get the aggregations attached to the proposal
     * (number of votes etc)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aggs()
    {
        return $this->hasMany('App\VoteAgg', 'proposal_id');
    }
    
    /**
     * Return one document as a feed item
     *
     * @return mixed
     */
    public function toFeedItem()
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->body)
            ->link('/proposal'.$this->id)
            ->author('Six Acts')
            ->updated($this->updated_at);
    }
    
    /**
     * Return all feed items
     *
     * @return \App\ProposalView[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getFeedItems()
    {
        return ProposalView::all();
    }
}
