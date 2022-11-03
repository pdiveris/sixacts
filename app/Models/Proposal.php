<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Proposal
 *
 * @category Model
 * @package App
 * @author Petros Diveris <petros@diveris.org>
 * @license Apache 2.0
 * @link https://www.diveris.org
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
 * @property-read Models\User $user
 * @property-read Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|Models\VoteAgg[] $aggs
 */
class Proposal extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category'];

    /**
     * Return the user associated with the Proposal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get category it belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get the aggregations attached to the proposal
     * (number of votes etc)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aggs()
    {
        return $this->hasMany('App\Models\VoteAgg');
    }

    /**
     * Populate use for inserts
     *
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $user = auth()->user();
            if ($user !== null && $user) {
                $post->user_id = auth()->user()->id;
            }
        });
    }
}
