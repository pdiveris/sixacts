<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vote
 *
 * @property int $id
 * @property int $user_id
 * @property int $proposal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereProposalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereUserId($value)
 * @mixin \Eloquent
 */
class Vote extends Model
{
    protected $fillable = ['user_id', 'proposal_id'];
}
