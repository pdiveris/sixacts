<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthData extends Model
{
    
    /**
     * @var array
     * e.g.
     *  twitter: 'theirId'=>'851049513880104960', 'nickname'=>'thongita', 'name'=>'Thongita Kapoor'
     *  facebook:
     *  google:
     */
    protected $fillable = [
        'id',
        'theirId',
        'token',
        'tokenSecret',
        'nickname',
        'name',
        'email',
        'avatar',
        'user'
    ];

}
