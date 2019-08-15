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
 * @property string $padolka
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
    
    /**
     * Get filtered Proposals (used in API and Controller)
     *
     * @param string $filter
     * @return \App\ProposalView[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function getFiltered($catsFilter = '1:2:3:4:5:6', $q = '', $userId = '', $type = '')
    {
    $query = "
    SELECT
        proposals.*,
        MATCH (proposals.title, proposals.body)
        AGAINST ('*{$q}*' IN BOOLEAN MODE) as score,
        category.sub_class as category_sub_class,
        category.class as category_class,
        category.short_title as category_short_title,
        user.display_name as user_display_name,
        user.name as user_name,
        aggs.total_votes as aggs_total_votes,
        aggs.total_dislikes as aggs_total_dislikes
        FROM proposals_view proposals
        LEFT JOIN categories category ON (category.id = proposals.category_id)
        LEFT JOIN users user ON (user.id = proposals.user_id)
        LEFT JOIN vote_aggs aggs ON (proposals.id = aggs.proposal_id) #cats_replacement #order_by";
    
        if ($catsFilter !== null &&  $catsFilter !== '') {
            // $cats = explode(':', $catsQuery);
            $cats = str_replace(':', ',', $catsFilter);
            $query = str_replace(
                ['#cats_replacement', '#order_by'],
                ["WHERE category_id  IN ($cats)", "ORDER BY score DESC"],
                $query
            );
        } else {
            $query = str_replace(['#cats_replacement', '**'], '', $query);
        }
        switch ($type) {
            case 'most':
                $query = str_replace(['#order_by'], ['ORDER BY num_votes DESC, score DESC'], $query);
                $props = ProposalView::raw($query)->get();
                break;
            case 'recent':
                $query = str_replace('#order_by', 'ORDER BY score desc, proposals.created_at desc', $query);
                $props = ProposalView::raw($query)->get();
                break;
            case 'current':
                $query = str_replace('#order_by', 'ORDER BY num_votes desc, score DESC', $query);
                $props = ProposalView::raw($query)->get();
                break;
            default:
                $query = str_replace(['#order_by'], ['ORDER BY score DESC '], $query);
                $props = \DB::select($query);
/*
                foreach ($props as $prop) {
                    echo json_encode($prop);
                }
                die;*/
        }
        foreach ($props as $prop) {
            // $prop->_kanga = 'Patriotic shit';
        }
        return $props;
    }

}
