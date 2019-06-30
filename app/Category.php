<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Category
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $short_title
 * @property string $class
 * @property string $sub_class
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereShortTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Category whereSubClass($value)
 */
class Category extends Model
{
    protected $fillable = ['id', 'title'];
}
