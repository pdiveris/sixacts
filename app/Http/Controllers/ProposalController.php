<?php

namespace App\Http\Controllers;

use App\Proposal;

/**
 * Class ProposalController
 *
 * @category Auth
 * @package  App\Http\Controllers
 * @author   Petros Diveris <petros@diveris.org>
 * @license  Apache 2.0
 * @link     https://www.diveris.org
 */
class ProposalController extends Controller
{
    /**
     * Vote action
     *
     * @param int $direction direction (up/down)
     *
     * @return void
     */
    public function vote($direction = 1)
    {
        //
    }
    
    /**
     * Vote up handler
     *
     * @return void
     */
    public function voteUp()
    {
        $this->vote();
    }
    
    /**
     * Vote down handler
     *
     * @return void
     */
    public function voteDown()
    {
        $this->vote(-1);
    }
    
    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $props = Proposal::all();
        foreach ($props as $prop) {
            if ($prop->category) {
                $prop->hasCategory = true;
            }
            if (count($prop->aggs)>0) {
                $prop->hasAggs = true;
            }
            if ($prop->user) {
                $prop->hasUser = true;
            }
        }
        return response()->json($props);
    }
    
}
