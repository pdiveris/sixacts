<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AuthData
 *
 * @property int $id
 * @property string $provider
 * @property string $scheme
 * @property string $meta
 * @property string $their_id
 * @property string $token
 * @property string|null $token_secret
 * @property string|null $refresh_token
 * @property string|null $expires_in
 * @property string $nickname
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property string $user
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereScheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereTheirId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereTokenSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereUser($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereExpiresIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuthData whereRefreshToken($value)
 */
class AuthData extends Model
{
    
    /**
     * @var array
     * e.g.
     *  twitter: 'their_id'=>'851049513880104960', 'nickname'=>'thongita', 'name'=>'Thongita Kapoor'
     *  facebook:
     *  google:
     */
    protected $fillable = [
        'id',
        'their_id',
        'token',
        'token_secret',
        'refresh_token',
        'expires_in',
        'nickname',
        'name',
        'email',
        'avatar',
        'user'
    ];

}
