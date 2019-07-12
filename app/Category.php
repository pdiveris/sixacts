<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder as Builder;

/**
 * App\Category
 *
 * @category Model
 * @package  App
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $short_title
 * @property string $class
 * @property string $sub_class
 *
 * @method static Builder|\App\Category newModelQuery()
 * @method static Builder|\App\Category newQuery()
 * @method static Builder|\App\Category query()
 * @method static Builder|\App\Category whereCreatedAt($value)
 * @method static Builder|\App\Category whereId($value)
 * @method static Builder|\App\Category whereTitle($value)
 * @method static Builder|\App\Category whereUpdatedAt($value)
 * @method static Builder|\App\Category whereClass($value)
 * @method static Builder|\App\Category whereShortTitle($value)
 * @method static Builder|\App\Category whereSubClass($value)
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $fillable = ['id', 'title'];
}
