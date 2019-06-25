<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AuthData extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\AuthData';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';
    
    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'System';
    
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'provider',
        'scheme',
        'their_id',
        'token',
        'token_secret',
        'refresh_token',
        'nickname',
        'name',
        'email',
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
     * @return array|string|null
     */
    public static function singularLabel()
    {
        return __('Auth Data');
    }
    
    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Auth Data');
    }
    
    /**
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Provider'), 'provider')->hideFromIndex(),
            Text::make(__('Scheme'), 'scheme'),
            Text::make(__('Their ID'), 'their_id'),
            Text::make(__('Token'), 'token')->hideFromIndex(),
            Text::make(__('Token Secret'), 'token_secret')->hideFromIndex(),
            Text::make(__('Refresh Token'), 'refresh_token')->hideFromIndex(),
            Text::make(__('Nickname'), 'nickname')->hideFromIndex(),
            Text::make(__('Name'), 'name'),
            Text::make(__('Email'), 'email'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
