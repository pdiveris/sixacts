<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder as Builder;

/**
 * App\Vote
 *
 * @category Model
 * @package App
 * @author Petros Diveris <petros@diveris.org>
 * @license Apache 2.0
 * @link https://www.diveris.org
 *
 * @property int $id
 * @property int $user_id
 * @property int $proposal_id
 * @property int $up
 * @property int $down
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Vote newModelQuery()
 * @method static Builder|Vote newQuery()
 * @method static Builder|Vote query()
 * @method static Builder|Vote whereCreatedAt($value)
 * @method static Builder|Vote whereId($value)
 * @method static Builder|Vote whereProposalId($value)
 * @method static Builder|Vote whereUpdatedAt($value)
 * @method static Builder|Vote whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereDown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereUp($value)
 * @mixin \Eloquent
 */
class Vote extends Model
{
    protected $fillable = ['user_id', 'proposal_id', 'up', 'down'];
}
