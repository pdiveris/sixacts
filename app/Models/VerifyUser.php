<?php
/**
 * VerifyUser Model
 *
 * User Verifications data model
 *
 * PHP version 7.2
 *
 * LICENSE: This source file is subject to version 2.0 of the Apache License
 * that is available through the world-wide-web at the following URI:
 * https://www.apache.org/licenses/LICENSE-2.0.
 *
 * @category  Model
 * @package   Auth
 * @author    Petros Diveris <petros@diveris.org>
 * @copyright 2019 Bentleyworks
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT: $Id$
 * @link      https://github.com/pdiveris/sixproposals/blob/master/app/Http/Controllers/Auth/LoginController.php
 * @see       Six Acts
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VerifyUser
 *
 * @category Model
 * @package App
 * @author Petros Diveris <petros@diveris.org>
 * @license Apache 2.0
 * @link https://www.diveris.org
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static Builder|VerifyUser newModelQuery()
 * @method static Builder|VerifyUser newQuery()
 * @method static Builder|VerifyUser query()
 * @method static Builder|VerifyUser whereCreatedAt($value)
 * @method static Builder|VerifyUser whereToken($value)
 * @method static Builder|VerifyUser whereUpdatedAt($value)
 * @method static Builder|VerifyUser whereUserId($value)
 * @mixin \Eloquent
 */
class VerifyUser extends Model
{
    protected $fillable = ['user_id', 'token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
