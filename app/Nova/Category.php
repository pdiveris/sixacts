<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Category';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';
    
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Core';
    
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];
    
    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $indexDefaultOrder = [
        'id' => 'asc'
    ];
    
    /**
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request request
     * @param \Illuminate\Database\Eloquent\Builder   $query   query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(\Laravel\Nova\Http\Requests\NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
    }
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            
            Text::make(__('Title'), 'title')->rules('required')
                ->hideFromIndex()
                ->sortable(),
            
            Text::make(__('Short title'), 'short_title')->rules('required')->sortable(),
            Text::make(__('Class'), 'class')->sortable(),
            Text::make(__('Sub class'), 'sub_class')->sortable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
