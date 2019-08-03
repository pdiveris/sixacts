<?php

namespace App\Http\Controllers;

use App\Events\MessagePosted;
use App\Proposal;
use App\ProposalView;
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
     * @throws \Exception
     */
    public function vote(Request $request)
    {
        $params = $request->all();
        $response = [];
        $ret = false;
    
        if (!array_key_exists('proposal_id', $params) ||
            !array_key_exists('direction', $params) ||
            !array_key_exists('user', $params)
        ) {
            return response()->json(['type' => 'error', 'message' => 'Bad request (missing data)']);
        }
        if (!$params['direction'] === 'up' || !$params['direction'] === 'down') {
            return response()->json(['type' => 'error', 'message' => 'Bad request (vote)']);
        }
    
        $vote = Vote::where('user_id', '=', $params['user']['user'])
            ->where('proposal_id', '=', $params['proposal_id'])
            ->first()
        ;
    
        $user = User::find($params['user']['user']);
        // if not user etc..
        if (null === $user) {
            return response()->json(['type' => 'error', 'message' => 'You need to be logged in to vote']);
        }

        if (null === $vote) {
            $vote = new \App\Vote();
            $vote->user_id = $params['user']['user'];
            $vote->proposal_id = $params['proposal_id'];
            
            if ($params['direction'] === 'dislike') {
                $vote->dislike = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your dislike has been noted NULL',
                    'action' => 'persist'
                ];
            } else {
                $response = [
                    'type' => 'success',
                    'message' => 'Your vote has been cast NULL',
                    'action' => 'persist'
                ];
                $vote->vote = 1;
            }
        }
        
        if ($vote->vote === 1 && $vote->dislike === 1) {
            if ($params['direction'] === 'dislike') {
                $vote->dislike = 0;
                $response = [
                    'type' => 'success',
                    'message' => 'Your have removed your dislike',
                    'action' => 'persist'
                ];
            } else {
                $response = [
                    'type' => 'success',
                    'message' => 'You\'ve removed your vote',
                    'action' => 'persist'
                ];
                $vote->vote = 0;
            }
        } elseif ($vote->vote === 1 && $vote->dislike === 0) {
            if ($params['direction'] === 'dislike') {
                $vote->dislike = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your dislike has been noted VOTE1DIS0',
                    'action' => 'persist'
                ];
            } else {
                $response = [
                    'type' => 'success',
                    'message' => 'You\'ve removed your vote',
                    'action' => 'persist'
                ];
                $vote->vote = 0;
            }
        } elseif ($vote->vote === 0 && $vote->dislike === 0) {
            if ($params['direction'] === 'dislike') {
                $vote->dislike = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your dislike has been noted VOTE0DIS0',
                    'action' => 'persist'
                ];
            } else {
                $vote->vote = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your vote has been cast VOTE0DIS0',
                    'action' => 'persist'
                ];
            }
        } elseif ($vote->vote === 0 && $vote->dislike === 1) {
            if ($params['direction'] === 'dislike') {
                $response = [
                    'type' => 'success',
                    'message' => 'Your dislike has been removed VOTE0DIS1 ',
                    'action' => 'remove'
                ];
            } else {
                $vote->vote = 1;
                $response = [
                    'type' => 'success',
                    'message' => 'Your vote has been added VOTE0DIS0',
                    'action' => 'persist'
                ];
            }
        }
        if ($response['action'] === 'remove') {
            $ret = $vote->delete();
        } elseif ($response['action'] === 'persist') {
            $ret = $vote->save();
        } elseif ($response['action'] === 'discard') {
            $ret = true;
        } else { // Horror!
        }
        
        if ($ret) {
            event(new MessagePosted($user, 'refresh'));
            return response()->json($response);
        }
        return response()->json(['type'=>'error', 'message'=>"Can't persist vote"]);
    }
    
    /**
     * Get all proposals with their aggregations (if they have any..)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $catsQuery = $request->get('cats', '');
        if (null !== $catsQuery && '' !== $catsQuery) {
            $cats = explode(':', $catsQuery);
            $props = ProposalView::whereIn('category_id', $cats)->get();
        } else {
            $props = ProposalView::all();
        }
        if ($userId > 0) {
            $props = StaticController::mergeProposalsWithVotes($props, $userId);
        }
        foreach ($props as $prop) {
            $prop->display = 'collapsed';
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
        $prop = Proposal::find((int)($proposalId));
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
    
    public function view($id)
    {
        if (is_numeric($id)) {
            $proposal = ProposalView::find($id);
        } else {
            $proposal = ProposalView::where('slug', '=', $id)
                                        ->first();
        }
        
        if (null === $proposal || ! $proposal) {
            abort(521);
        }
        return view('static.proposal', ['proposal'=>$proposal]);
    }
}
