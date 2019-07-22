<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Proposal;
use App\User;
use App\Vote;
use Illuminate\Http\Request;

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
    public function vote(Request $request)
    {
        $params = $request->all();
        $response = [];
        dump($params);
        if (!array_key_exists('proposal_id', $params) ||
            !array_key_exists('direction', $params) ||
            !array_key_exists('user', $params)
        ) {
            return response()->json(['error'=>'Bad request (missing data)']);
        }
        if (!$params['direction'] == 'up' || !$params['direction'] == 'down') {
            return response()->json(['error'=>'Bad request (vote)']);
        }
        
        $vote = Vote::where('user_id', '=', $params['user']['user'])
                        ->where('proposal_id', '=', $params['proposal_id'])
                        ->first();
        
        $user = User::find($params['user']['user']);
        // if not user etc..
        
        if (null !== $vote) {
            if ($params['direction'] == 'up') {
                if ($vote->up > 0) {
                    return response()->json(['warning' => 'You have already voted up this proposal']);
                }
                $vote->down = 0;
                $vote->up = 1;
                $response = ['success'=>'from 0 to 1'];
            }
            if ($params['direction'] == 'down') {
                if ($vote->down > 0) {
                    return response()->json(['warning' => 'You have already voted down this proposal']);
                }
                $vote->down = 1;
                $vote->up = 0;
                $response = ['success'=>'from 0 to 1'];
            }
        } else {
            $vote = new \App\Vote();
            $vote->user_id = $params['user']['user'];
            $vote->proposal_id = $params['proposal_id'];
            if ($params['direction']=='down') {
                $vote->down = 1;
                $vote->up = 0;
            } else {
                $vote->up = 1;
                $vote->down = 0;
            }
            $response = ['success'=>[$vote->getAttributes()]];
        }
        $ret = $vote->save();
        if ($ret) {
            event(new MessagePosted($user, 'refresh'));
            return response()->json($response);
        }
        return response()->json(['fatal'=>"Can't persist vote"]);
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
    
    /**
     * Find by ID and show a proposal and qhiq it
     *
     * @param int $proposalId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($proposalId = 0)
    {
        $prop = Proposal::find(\intval($proposalId));
        if (null===$prop) {
            return response()->json(['message' => 'Not Found.'], 404);
        }
        dump($prop);
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
