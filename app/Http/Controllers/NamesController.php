<?php

namespace App\Http\Controllers;

use App\Name;
use App\RandomNames;

/**
 * Class NameController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class NamesController extends Controller
{
    
    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $props = Name::where('id', '>', 1)
                        ->limit(15)
                        ->get();

        return response()->json($props);
    }
    
    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function random()
    {
        $props = RandomNames::where('id', '>', 1)
            ->limit(15)
            ->get();
        
        return response()->json($props);
    }
    
    /**
     * Find by ID and show a proposal and qhiq it
     *
     * @param int $proposalId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($proposalId = 0)
    {
        $prop = Name::find(\intval($proposalId));
        if (null===$prop) {
            return response()->json(['message' => 'Not Found.'], 404);
        }
        if ($prop->category) {
            $prop->hasCategory = true;
        }
        if (count($prop->aggs)>0) {
            $prop->hasAggs = true;
        }
        if ($prop->user) {
            $prop->hasUser = true;
        }
        return response()->json($prop);
    }
}
