<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Proposal
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $category_id
 * @property string $colour
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proposal whereUserId($value)
 * @mixin \Eloquent
 */
class Proposal extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
