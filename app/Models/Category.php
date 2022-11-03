<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @category Model
 * @package App
 * @author Petros Diveris <petros@diveris.org>
 * @license Apache 2.0
 * @link https://www.diveris.org
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $short_title
 * @property string $class
 * @property string $sub_class
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereClass($value)
 * @method static Builder|Category whereShortTitle($value)
 * @method static Builder|Category whereSubClass($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|Models\Proposal[] $proposals
 * @property string|null $class_was
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereClassWas($value)
 */
class Category extends Model
{
    protected $fillable = ['id', 'title'];

    /**
     * Return proposals
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proposals()
    {
        return $this->hasMany('App\Models\Proposal');
    }
}
