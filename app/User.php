<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use \Illuminate\Database\Eloquent\Builder as Builder;

/**
 * App\User
 *
 * @category Model
 * @package App
 * @author Petros Diveris <petros@diveris.org>
 * @license Apache 2.0
 * @link https://www.diveris.org *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $display_name
 * @property string|null $avatar
 * @property string|null $social_avatar
 * @property string|null $social_avatar_large
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $verified
 * @method static Builder|\App\User newModelQuery()
 * @method static Builder|\App\User newQuery()
 * @method static Builder|\App\User query()
 * @method static Builder|\App\User whereCreatedAt($value)
 * @method static Builder|\App\User whereEmail($value)
 * @method static Builder|\App\User whereEmailVerifiedAt($value)
 * @method static Builder|\App\User whereId($value)
 * @method static Builder|\App\User whereName($value)
 * @method static Builder|\App\User wherePassword($value)
 * @method static Builder|\App\User whereRememberToken($value)
 * @method static Builder|\App\User whereUpdatedAt($value)
 * @method static Builder|\App\User whereAvatar($value)
 * @method static Builder|\App\User whereDisplayName($value)
 * @method static Builder|\App\User whereSocialAvatar($value)
 * @method static Builder|\App\User whereSocialAvatarLarge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|
 * \App\User whereVerified($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|
 * \Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|
 * \App\Proposal[] $proposals
 * @property-read \App\VerifyUser $verifyUser
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Proposal[] $proposals
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerified($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'avatar', 'social_avatar', 'social_avatar_large'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $dispatchesEvents = [
        'created' => Events\UserCreatedEvent::class,
    ];
    
    /**
     * Return proposals
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany('App\Proposal');
    }
    
    /**
     * Return user's verify "record"
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    
    /**
     * Get the JWT identifier for user
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    /**
     * Get the JWT custom claims as per spec
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * Set the password attribute
     *
     * @param string $password password
     *
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = $password;
        }
    }
}
