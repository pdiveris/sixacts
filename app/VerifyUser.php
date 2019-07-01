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
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VerifyUser
 *
 * @package App
 * @property int $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VerifyUser whereUserId($value)
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
        return $this->belongsTo('App\User', 'user_id');
    }
}