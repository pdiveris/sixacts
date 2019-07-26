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
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function vote(Request $request)
    {
        $params = $request->all();
        $response = [];
        if (!array_key_exists('proposal_id', $params) ||
            !array_key_exists('direction', $params) ||
            !array_key_exists('user', $params)
        ) {
            return response()->json(['type'=>'error', 'message'=>'Bad request (missing data)']);
        }
        if (!$params['direction'] == 'up' || !$params['direction'] == 'down') {
            return response()->json(['type'=>'error', 'message'=>'Bad request (vote)']);
        }
        
        $vote = Vote::where('user_id', '=', $params['user']['user'])
                        ->where('proposal_id', '=', $params['proposal_id'])
                        ->first();
        
        $user = User::find($params['user']['user']);
        // if not user etc..
        if (null !== $vote) {
            if ($params['direction'] == 'up') {
                if ($vote->up > 0) {
                    return response()->json([
                            'type'=>'warning',
                            'message' => 'You have already voted up this proposal'
                        ]);
                }
                if ($vote->down == 1) {     // correct
                    $vote->down = 0;
                    $vote->up = 0;
                    $response = ['type'=>'success', 'message'=>'You just removed your negative vote'];
                } else {                    // correct
                    $vote->down = 0;
                    $vote->up = 1;
                    $response = ['type'=>'success', 'message'=>'Vote up added'];
                }
            }
            if ($params['direction'] == 'down') {
                if ($vote->down > 0) {
                    return response()->json([
                        'message' => 'You have already voted down this proposal',
                        'type'=>'warning',
                    ]);
                }
                if ($vote->up == 1) {       // correct
                    $vote->up = 0;
                    $response = [
                        'type'=>'success',
                        'message'=>'You just removed your vote for this proposal'
                    ];
                } else {                    // correct
                    $vote->up = 0;
                    $vote->down = 1;
                    $response = [
                        'type'=>'success',
                        'message'=>'You have now cast a negative vote for this proposal'
                    ];
                }
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
            $response = ['type'=>'success', 'message'=>"Proposal voted ".$params['direction']];
        }
        $ret = $vote->save();
        
        if ($ret) {
            event(new MessagePosted($user, 'refresh'));
            return response()->json($response);
            return response()->json($response);
        }
        return response()->json(['type'=>'error', 'message'=>"Can't persist vote"]);
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
            return response()->json(['type'=>'error', 'error' => 'Not Found.'], 404);
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
